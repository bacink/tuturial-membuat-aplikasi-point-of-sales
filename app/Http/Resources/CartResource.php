<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'id' => $this->id_penjualan_detail,
            'id_penjualan' => $this->id_penjualan,
            'id_produk' => $this->id_produk,
            'harga_jual' => $this->harga_jual,
            'harga_jual_rp' => format_uang($this->harga_jual),
            'jumlah' => $this->jumlah,
            'diskon' => $this->diskon,
            'subtotal' => $this->subtotal,
            'subtotal_rp' => format_uang($this->subtotal),
            'nama_produk' => $this->produk->nama_produk,
            'kode_produk' => $this->produk->kode_produk
        ];
    }
}
