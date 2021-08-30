<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProdukResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id_produk'=>$this->id_produk,
            'id_kategori'=>$this->id_kategori,
            'kategori'=>$this->kategori,
            'kode_produk'=>$this->kode_produk, 
            'nama_produk'=>$this->nama_produk, 
            'merk'=>$this->merk,  
            'harga_jual'=>$this->harga_jual,
        ];
    }
}
