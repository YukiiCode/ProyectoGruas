import { jsPDF } from 'jspdf';

export function generarRecibo(retirada) {
  const doc = new jsPDF();
  doc.text(`Recibo de Retirada`, 10, 10);
  doc.text(`Nombre: ${retirada.nombre}`, 10, 20);
  doc.text(`Importe Total: ${retirada.total} €`, 10, 30);
  doc.save('recibo.pdf');
}