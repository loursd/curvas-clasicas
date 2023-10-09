<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\usuario_Model;
use App\Controllers\BaseController;

class usuario_controller extends BaseController
{
    public function __construct()
    {
        helper(['form', 'url']);
    }
    public function create()
    {
        $dato['titulo'] = 'Registro';
        echo view('front/head_view', $dato);
        echo view('front/navbar_view');
        echo view('back/usuarios/registro');
        echo view('front/footer_view');
    }

    //Las validaciones se cambiaron en system/Language/en/Validation
    public function formValidation()
    {
        $input = $this->validate(
            [
                'nombre' => 'required|min_length[3]|alpha_space',
                'apellido' => 'required|min_length[3]|max_length[25]|alpha_space',
                'usuario' => 'required|min_length[3]',
                'email' => 'required|min_length[4]|max_length[100]|valid_email|is_unique[usuarios.email]',
                'pass' => 'required|min_length[5]|max_length[10]'
            ],
        );
        $formModel = new usuario_Model();

        if (!$input) {
            $data['titulo'] = 'Registro';
            echo view('front/head_view', $data);
            echo view('front/navbar_view');
            echo view('back/usuarios/registro', ['validation' => $this->validator]);
            echo view('front/footer_view');
        } else {
            $formModel->save([
                'nombre' => $this->request->getVar('nombre'),
                'apellido' => $this->request->getVar('apellido'),
                'usuario' => $this->request->getVar('usuario'),
                'email' => $this->request->getVar('email'),
                'pass' => password_hash($this->request->getVar('pass'), PASSWORD_DEFAULT)
                //password_hash() crea un nuevo hash de contraseña usando un algoritmo de hash de único sentido
            ]);
            //flashdata funciona solo en redirigir la función en el controlador en la vista de carga
            session()->setFlashdata('success', 'El usuario ha sido registrado con éxito');
            return redirect()->to(base_url('/registro'));
        }
    }
}
