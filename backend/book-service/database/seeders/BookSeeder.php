<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'judul_buku' => 'Menembus Langit',
                'penulis_buku' => 'Tere Liye',
                'penerbit_buku' => 'Gramedia Pustaka Utama',
                'tahun_terbit_buku' => 2020
            ],
            [
                'judul_buku' => 'Jejak Sang Petualang',
                'penulis_buku' => 'Dee Lestari',
                'penerbit_buku' => 'Bentang Pustaka',
                'tahun_terbit_buku' => 2018
            ],
            [
                'judul_buku' => 'Filosofi Kopi',
                'penulis_buku' => 'Dee Lestari',
                'penerbit_buku' => 'Truedee Books',
                'tahun_terbit_buku' => 2006
            ],
            [
                'judul_buku' => 'Negeri 5 Menara',
                'penulis_buku' => 'Ahmad Fuadi',
                'penerbit_buku' => 'Gramedia Pustaka Utama',
                'tahun_terbit_buku' => 2009
            ],
            [
                'judul_buku' => 'Laut Bercerita',
                'penulis_buku' => 'Leila S. Chudori',
                'penerbit_buku' => 'KPG (Kepustakaan Populer Gramedia)',
                'tahun_terbit_buku' => 2017
            ],
            [
                'judul_buku' => 'Bumi',
                'penulis_buku' => 'Tere Liye',
                'penerbit_buku' => 'Gramedia Pustaka Utama',
                'tahun_terbit_buku' => 2014
            ],
            [
                'judul_buku' => 'Hujan',
                'penulis_buku' => 'Tere Liye',
                'penerbit_buku' => 'Gramedia Pustaka Utama',
                'tahun_terbit_buku' => 2016
            ],
            [
                'judul_buku' => 'Rindu',
                'penulis_buku' => 'Tere Liye',
                'penerbit_buku' => 'Republika Penerbit',
                'tahun_terbit_buku' => 2014
            ],
            [
                'judul_buku' => 'Laskar Pelangi',
                'penulis_buku' => 'Andrea Hirata',
                'penerbit_buku' => 'Bentang Pustaka',
                'tahun_terbit_buku' => 2005
            ],
            [
                'judul_buku' => 'Perahu Kertas',
                'penulis_buku' => 'Dee Lestari',
                'penerbit_buku' => 'Bentang Pustaka',
                'tahun_terbit_buku' => 2009
            ]
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
} 