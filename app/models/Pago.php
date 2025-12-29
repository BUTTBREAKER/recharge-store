<?php
class Pago extends BaseModel {
    public function registrar($data) {
        $sql = "INSERT INTO pagos (pedido_id, referencia, comprobante, provider, estado) 
                VALUES (:pedido_id, :referencia, :comprobante, :provider, 'pendiente')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function obtenerPorPedido($pedido_id) {
        $stmt = $this->db->prepare("SELECT * FROM pagos WHERE pedido_id = ?");
        $stmt->execute([$pedido_id]);
        return $stmt->fetch();
    }

    public function actualizarEstado($id, $estado) {
        $stmt = $this->db->prepare("UPDATE pagos SET estado = ? WHERE id = ?");
        return $stmt->execute([$estado, $id]);
    }
}
