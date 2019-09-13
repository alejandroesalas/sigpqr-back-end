<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Registro de perfiles
        DB::table('profiles')->insert([
            'name' => 'administrador',
            'description' => 'Gestiona la app en su totalidad.'
        ]);
        DB::table('profiles')->insert([
            'name' => 'coordinador',
            'description' => 'Coordina un programa. encargado de tramitar las solicitudes generadas por los usuarios.'
        ]);
        DB::table('profiles')->insert([
            'name' => 'estudiante',
            'description' => 'Usuario regular de la app. Realiza solicitudes a un programa en concreto.'
        ]);
        DB::table('profiles')->insert([
            'name' => 'docente',
            'description' => 'Usuario candidato a ser cordinador.'
        ]);
        // Registro de usuarios
        DB::table('users')->insert([
            'name' => 'admin',
            'lastname' => 'admin',
            'email' => 'emailsigpqr@gmail.com',
            'id_type' => User::CC_TYPE,
            'id_num' => '1234',
            'password' => bcrypt('admin'),
            'verified' => 1,
            'status' => User::ACTIVE_STATE,
            'admin' => User::ADMIN_USER,
            'program_id' => null,
            'profile_id' => User::ADMIN_PROFILE
        ]);
        DB::table('users')->insert([
            'name' => 'Jorge',
            'lastname' => 'Marquez',
            'email' => 'Jorge.Marquez@cecar.edu.co',
            'id_type' => User::CC_TYPE,
            'id_num' => '123',
            'password' => bcrypt('123456'),
            'verified' => 1,
            'status' => User::ACTIVE_STATE,
            'admin' => User::REGULAR_USER,
            'program_id' => null,
            'profile_id' => User::COORDINATOR_PROFILE
        ]);

        // Registro de perfiles
        DB::table('request_types')->insert([
            'type' => 'petición',
            'description' => 'Solicitudes realizadas  por los estudiantes.'
        ]);
        DB::table('request_types')->insert([
            'type' => 'queja',
            'description' => 'Solicitudes realizadas  por los estudiantes.'
        ]);
        DB::table('request_types')->insert([
            'type' => 'reclamo',
            'description' => 'Solicitudes realizadas  por los estudiantes.'
        ]);
        // Registro de facultad
        DB::table('faculties')->insert(['name' => 'Facultad de Ciencias Básicas, Ingenierías y Arquitectura']);
        // Registro de programas
        DB::table('programs')->insert([
            'name' => 'Ingeniería Industrial',
            'faculty_id' => 1,
            'coordinator_id' => 2,
        ]);
        DB::table('programs')->insert([
            'name' => 'Ingeniería de Sistemas',
            'faculty_id' => 1,
            'coordinator_id' => 2,
        ]);
        // Registro de usuarios
        for ($i=2; $i <= 4; $i++) {
            DB::table('users')->insert([
                'name' => 'estudiante '.$i,
                'lastname' => 'apellido estudiante '.$i,
                'email' => 'correo'.$i.'@gmail.com',
                'id_type' => User::CC_TYPE,
                'id_num' => $i,
                'password' => bcrypt('password'),
                'verified' => 1,
                'status' => User::ACTIVE_STATE,
                'admin' => User::REGULAR_USER,
                'program_id' => 1,
                'profile_id' => User::STUDENT_PROFILE,
                'verification_token' => Str::random(40),
            ]);
        }
        // Registro de peticion
        DB::table('requests')->insert([
            'title' => 'titulo1',
            'description' => 'parrafo1',
            'status' => 'abierta',
            'request_type_id' => 1,
            'program_id' => 1,
            'student_id' => 5,
        ]);
        // Registro de respuesta
        DB::table('responses')->insert([
            'title' => 'titulo1',
            'description' => 'parrafo1',
            'status_response' => 1,
            'type' => 1,
            'request_id' => 1,
            'user_id' => 5,
            'user_email'=>'correo4@gmail.com',
            'type_user' => 'estudiante',
        ]);
        // Registro de peticion
        DB::table('requests')->insert([
            'title' => 'titulo2',
            'description' => 'parrafo2',
            'status' => 'abierta',
            'request_type_id' => 2,
            'program_id' => 1,
            'student_id' => 3,
        ]);
        // Registro de respuesta
        DB::table('responses')->insert([
            'title' => 'titulo2',
            'description' => 'parrafo2',
            'status_response' => 1,
            'type' => 2,
            'request_id' => 2,
            'user_id' => 3,
            'user_email'=>'correo2@gmail.com',
            'type_user' => 'estudiante',
        ]);
        // Registro de peticion
        DB::table('requests')->insert([
            'title' => 'titulo3',
            'description' => 'parrafo3',
            'status' => 'abierta',
            'request_type_id' => 3,
            'program_id' => 1,
            'student_id' => 4,
        ]);
        // Registro de respuesta
        DB::table('responses')->insert([
            'title' => 'titulo3',
            'description' => 'parrafo3',
            'status_response' => 0,
            'type' => 3,
            'request_id' => 3,
            'user_id' => 4,
            'user_email'=>'correo3@gmail.com',
            'type_user' => 'estudiante',
        ]);

    }
}
