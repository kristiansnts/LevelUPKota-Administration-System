<?php

return [
    'roles' => [
        'super_admin' => 'super_admin',
        'guest' => 'guest',
    ],
    'resources' => [
        'mail' => [
            'label' => 'Catatan Arsip',
            'group' => 'Arsip',
            'slug' => 'mail',
        ],
        'transaction' => [
            'create' => 'Catat Transaksi',
            'edit' => 'Ubah Transaksi',
            'delete' => 'Hapus Transaksi',
        ],
        'transactionType' => [
            'create' => 'Tambah Tipe Transaksi',
            'edit' => 'Ubah Tipe Transaksi',
            'delete' => 'Hapus Tipe Transaksi',
        ],
        'transactionCategory' => [
            'create' => 'Tambah Kategori Transaksi',
            'edit' => 'Ubah Kategori Transaksi',
            'delete' => 'Hapus Kategori Transaksi',
        ],
    ],
];
