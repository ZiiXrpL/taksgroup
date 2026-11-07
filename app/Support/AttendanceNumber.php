<?php

namespace App\Support;

use Illuminate\Validation\Rule;

/**
 * Aturan & normalisasi nomor absen yang dipakai bersama oleh form profil
 * siswa dan form admin, agar validasinya selalu sama.
 */
class AttendanceNumber
{
    public const MIN = 1;

    public const MAX = 99;

    /**
     * Ubah input seperti "03" menjadi 3. Validator `integer` bawaan Laravel
     * menolak angka berawalan nol, padahal contoh di antarmuka adalah "03".
     * Input yang bukan murni angka dibiarkan apa adanya agar ditolak validator.
     */
    public static function normalize(mixed $value): mixed
    {
        if (is_string($value)) {
            $value = trim($value);

            if ($value === '') {
                return null;
            }

            if (ctype_digit($value)) {
                return (int) $value;
            }
        }

        return $value;
    }

    /**
     * @return array<int, mixed>
     */
    public static function rules(?int $classId, ?int $ignoreUserId = null, bool $required = false): array
    {
        $unique = Rule::unique('users', 'attendance_number')
            ->where(fn ($query) => $query->where('class_id', $classId));

        if ($ignoreUserId !== null) {
            $unique->ignore($ignoreUserId);
        }

        return [
            $required ? 'required' : 'nullable',
            'integer',
            'min:' . self::MIN,
            'max:' . self::MAX,
            $unique,
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function messages(string $field = 'attendance_number'): array
    {
        return [
            "{$field}.required" => 'Nomor absen wajib diisi.',
            "{$field}.integer" => 'Nomor absen harus berupa angka bulat positif.',
            "{$field}.min" => 'Nomor absen minimal ' . self::MIN . '.',
            "{$field}.max" => 'Nomor absen maksimal ' . self::MAX . '.',
            "{$field}.unique" => 'Nomor absen ini sudah dipakai siswa lain di kelas yang sama.',
        ];
    }
}
