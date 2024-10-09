<?php

require_once '../app/Models/StudentModel.php';

class EleveController {
    private $eleveModel;

    public function __construct($conn) {
        $this->eleveModel = new Eleve($conn);
    }

    public function index() {
        $eleves = $this->eleveModel->getAll();
        require '../app/views/eleves/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->eleveModel->create($_POST['nom'], $_POST['prenom'], $_POST['date_naissance'], $_POST['email'], $_POST['telephone']);
            header('Location: /index.php?action=index');
        }
        require '../app/Views/eleves/create.php';
    }

    public function edit($id) {
        $eleve = $this->eleveModel->getById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->eleveModel->update($id, $_POST['nom'], $_POST['prenom'], $_POST['date_naissance'], $_POST['email'], $_POST['telephone']);
            header('Location: /index.php?action=index');
        }
        require '../app/Views/eleves/edit.php';
    }

    public function delete($id) {
        $this->eleveModel->delete($id);
        header('Location: /index.php?action=index');
    }
}

