<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AbsenController extends Controller
{
    public function absen(Request $request)
    {
        $validator = Validator::make($request -> all(), [
            'nama_ustadz' => 'required',
            'waktu_kehadiran' => 'required',
            ]
        );

        if($validator -> fails()) {
            return response() -> json($validator->errors(), 400);
        }

        $absen = Absen::create([
            'nama_ustadz' => $request -> nama_ustadz,
            'waktu_kehadiran' => $request -> waktu_kehadiran,
        ]);

        return response() -> json([
            'success' => true,
            'message' => 'Absen berhasil ditambahkan',
            'data' => $absen
        ]);
    }
}
