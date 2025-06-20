<?php

namespace App\Http\Controllers\Tenant;

use Exception;

use App\Traits\OfflineTrait;
use Illuminate\Http\Request;
use App\Models\Tenant\Person;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Catalogs\Country;
use App\Models\Tenant\Taxis\Propietarios;
use App\Models\Tenant\Catalogs\AttributeType;
use App\Http\Requests\Tenant\PropietarioRequest;
use App\Http\Resources\Tenant\PropietarioResource;
use App\Http\Resources\Tenant\PropietarioCollection;
use App\Models\Tenant\Catalogs\IdentityDocumentType;

class PropietariosController extends Controller
{

    use OfflineTrait;

    public function index()
    {
        return view('tenant.taxis.propietarios.index');
    }

    public function columns()
    {
        return [
            'name' => 'Nombre',
            'number' => 'Número de documento',
            'identity_document_type' => 'Tipo de documento',
            'address' => 'Dirección',
            'telephone' => 'Teléfono',
            'email' => 'Correo electrónico',
            'department' => 'Departamento',
            'province' => 'Provincia',
            'district' => 'Distrito',
        ];
    }

    public function records(Request $request)
    {
        $records = Propietarios::where($request->column, 'like', "%{$request->value}%")
            ->orderBy('name');


        return new PropietarioCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function tables()
    {
        $countries = Country::whereActive()->orderByDescription()->get();
        $attribute_types = AttributeType::whereActive()->orderByDescription()->get();
        $identity_document_types = IdentityDocumentType::whereActive()->get();
        $configuration = Configuration::first();
        $locations = func_get_locations();
        $api_service_token = \App\Models\Tenant\Configuration::getApiServiceToken();


        return compact(
            'countries',
            'attribute_types',
            'identity_document_types',
            'configuration',
            'api_service_token',
            'locations',
        );
    }

    public function record($id)
    {
        $record = new PropietarioResource(Propietarios::findOrFail($id));

        return $record;
    }

    public function store(PropietarioRequest $request)
    {
        try {
            $id = $request->input('id');
            $propietario = Propietarios::firstOrNew(['id' => $id]);
            $data = $request->all();
            unset($data['id']);
            $propietario->fill($data);

            $location_id = $request->input('location_id');
            if (is_array($location_id) && count($location_id) === 3) {
                $propietario->district_id = $location_id[2];
                $propietario->province_id = $location_id[1];
                $propietario->department_id = $location_id[0];
            }

            $propietario->save();

            // Verificar si existe una persona con el mismo número de documento
            $person = Person::where('number', $propietario->number)
                ->where('identity_document_type_id', $propietario->identity_document_type_id)
                ->first();
            $person_id = $person ? $person->id : null;
            // Si no existe la persona, crear una nueva con los datos del propietario
            if (!$person) {
                $person = new Person();
                $person->type = 'customers'; // Asignamos el tipo de persona
                $person->number = $propietario->number;
                $person->name = $propietario->name;
                $person->identity_document_type_id = $propietario->identity_document_type_id;
                $person->email = $propietario->email;
                $person->telephone = $propietario->telephone;
                $person->address = $propietario->address;
                $person->country_id = $propietario->country_id;
                $location_id = $request->input('location_id');
                if (is_array($location_id) && count($location_id) === 3) {
                    $person->district_id = $location_id[2];
                    $person->province_id = $location_id[1];
                    $person->department_id = $location_id[0];
                }
                $person_id = $person->id;
                $person->save();
            }

            $propietario->person_id = $person_id;
            $propietario->save();

            $msg = ($id) ? 'Propietario editado con éxito' : 'Propietario registrado con éxito';

            return [
                'success' => true,
                'message' => $msg,
                'id' => $propietario->id
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error inesperado, no se pudo guardar el Propietario: ' . $e->getMessage()
            ];
        }
    }

    public function destroy($id)
    {
        try {

            $propietario = Propietarios::findOrFail($id);
            $propietario->delete();

            return [
                'success' => true,
                'message' => 'Propietario eliminado con éxito'
            ];
        } catch (Exception $e) {
            dd($e);
            return ($e->getCode() == '23000') ? ['success' => false, 'message' => 'El Propietario esta siendo usado por otros registros, no puede eliminar'] : ['success' => false, 'message' => 'Error inesperado, no se pudo eliminar el Propietario'];
        }
    }

    /**
     * Método para búsqueda de propietarios
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $term = $request->input('term');
        $propietarios = Propietarios::where('enabled', true)
            ->where(function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('number', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            })
            ->orderBy('name')
            ->take(15)
            ->get()
            ->map(function ($propietario) {
                return $propietario->getCollectionData();
            });

        return response()->json($propietarios);
    }
}
