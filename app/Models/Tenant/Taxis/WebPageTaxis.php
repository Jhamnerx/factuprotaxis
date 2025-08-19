<?php

namespace App\Models\Tenant\Taxis;


use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Taxis\Marca;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class WebPageTaxis extends ModelTenant
{
    use UsesTenantConnection;

    protected $table = 'web_page_taxis';
    protected $fillable = [
        'title_services',
        'services',
        'about',
        'contact_image',
        'client_says',
        'why_choose',
    ];
    protected $casts = [
        'services' => 'array',
        'about' => 'array',
        'client_says' => 'array',
        'why_choose' => 'array'
    ];

    public function getCollectionData()
    {

        return [
            'id'  => $this->id,
            'title_services' => $this->title_services,
            'services' => is_array($this->services) ? array_map(function ($service) {
                return [
                    'name' => $service['name'],
                    'description' => $service['description'],
                    'image' => $service['image'],
                    'image_url' => $service['image'] ? asset('storage/uploads/services/' . $service['image']) : asset('tenant/images/delivery-man.png'),
                ];
            }, $this->services) : [],
            'about' => is_array($this->about) ? [
                'text' => $this->about['text'],
                'image' => $this->about['image'],
                'image_url' => $this->about['image'] ? asset('storage/uploads/about/' . $this->about['image']) : asset('tenant/images/about-img.png'),
            ] : [],
            'contact_image' => $this->contact_image,
            'contact_image_url' => $this->contact_image ? asset('storage/uploads/contact/' . $this->contact_image) : asset('tenant/images/contact-img.png'),
            'client_says' => is_array($this->client_says) ? array_map(function ($client) {
                return [
                    'name' => $client['name'],
                    'text' => $client['text'],
                    'image' => $client['image'],
                    'image_url' => $client['image'] ? asset('storage/uploads/client/' . $client['image']) : asset('tenant/images/client.png'),
                ];
            }, $this->client_says) : [],
            'why_choose' => is_array($this->why_choose) ? array_map(function ($item) {
                return [
                    'title' => $item['title'],
                    'description' => $item['description'],
                ];
            }, $this->why_choose) : [],
        ];
    }
}
