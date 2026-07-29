<?php

require_once SERVERPATH.'modelos/mainModel.php';

class EmpresaModelo extends mainModel{

   /*-------- Modelo datos empresa --------- */
   protected static function datos_empresa_modelo(){
        $sql = mainModel::conectar()->prepare("SELECT * FROM empresa;");
        $sql->execute();
        return $sql;
   }

   /*-------- Modelo registrar datos empresa --------- */
   protected static function agregar_empresa_modelo($datos){
        $sql = mainModel::conectar()->prepare("INSERT INTO empresa(empresa_nombre, empresa_email, empresa_telefono, empresa_direccion) VALUES(:Nombre, :Email, :Telefono, :Direccion)");
        $sql->bindParam(':Nombre', $datos['Nombre']);
        $sql->bindParam(':Email', $datos['Email']);
        $sql->bindParam(':Telefono', $datos['Telefono']);
        $sql->bindParam(':Direccion', $datos['Direccion']);
        $sql->execute();
        return $sql;
   }

   /*-------- Modelo actualizar datos empresa --------- */
   protected static function actualizar_empresa_modelo($datos){
           $sql = mainModel::conectar()->prepare("UPDATE empresa SET empresa_nombre = :Nombre, empresa_email = :Email, empresa_telefono = :Telefono, empresa_direccion = :Direccion WHERE empresa_id = :ID;");
           $sql->bindParam(':Nombre', $datos['Nombre']);
           $sql->bindParam(':Email', $datos['Email']);
           $sql->bindParam(':Telefono', $datos['Telefono']);
           $sql->bindParam(':Direccion', $datos['Direccion']);
           $sql->bindParam(':ID', $datos['ID']);
           $sql->execute();
           return $sql;
   }
}
?>