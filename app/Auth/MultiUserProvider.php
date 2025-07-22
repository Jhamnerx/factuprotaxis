<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\Tenant\Taxis\Propietarios;
use App\Models\Tenant\Taxis\Conductor;

class MultiUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (
            empty($credentials) ||
            (count($credentials) === 1 && array_key_exists('password', $credentials))
        ) {
            return null;
        }

        // Obtener email y tipo de usuario
        $email = $credentials['email'] ?? null;
        $userType = $credentials['user_type'] ?? null;

        if (!$email || !$userType) {
            return null;
        }

        // Buscar en el modelo correspondiente según el tipo de usuario
        switch ($userType) {
            case 'propietario':
                $query = Propietarios::query();
                break;
            case 'conductor':
                $query = Conductor::query();
                break;
            default:
                return null;
        }

        // Aplicar restricciones de búsqueda
        foreach ($credentials as $key => $value) {
            if (!str_contains($key, 'password') && $key !== 'user_type') {
                $query->where($key, $value);
            }
        }

        return $query->first();
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        // Intentar buscar en ambos modelos
        $user = Propietarios::find($identifier);
        if ($user) {
            return $user;
        }

        return Conductor::find($identifier);
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed   $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        // Intentar buscar en ambos modelos
        $user = Propietarios::where('id', $identifier)
            ->where('remember_token', $token)
            ->first();
        if ($user) {
            return $user;
        }

        return Conductor::where('id', $identifier)
            ->where('remember_token', $token)
            ->first();
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);

        // Verificar si el usuario tiene el método save
        if (method_exists($user, 'save')) {
            $user->save();
        }
    }
}
