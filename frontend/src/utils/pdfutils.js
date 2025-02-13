import { PdfMakeWrapper, Txt, Table, Columns } from "pdfmake-wrapper";
import * as pdfFonts from "pdfmake/build/vfs_fonts";

// Configurar fuentes para pdfmake
PdfMakeWrapper.setFonts(pdfFonts);

export const generarRecibo = (datos) => {
  const { nombre, nif, fecha, total, items } = datos;

  // Crear instancia de PdfMakeWrapper
  const pdf = new PdfMakeWrapper();

  // Encabezado
  pdf.add(
    new Columns([
      { image: "logo_base64", width: 80, alignment: "left" }, // Reemplaza "logo_base64" con tu logo
      { text: "RECIBO DE RETIRADA", fontSize: 18, bold: true, color: "#007bff", alignment: "right" },
    ]).columnGap(10).end
  );

  pdf.add("\n");

  // Información del cliente
  pdf.add(new Txt("INFORMACIÓN DEL CLIENTE").bold().fontSize(14).end);
  pdf.add(
    new Table([
      ["Nombre:", nombre],
      ["NIF:", nif],
      ["Fecha:", fecha],
      ["Total:", `${total} €`],
    ])
      .layout("noBorders")
      .margin([0, 0, 0, 20])
      .end
  );

  // Detalles de la retirada
  pdf.add(new Txt("DETALLES DE LA RETIRADA").bold().fontSize(14).end);
  pdf.add(
    new Table([
      ["ID", "Descripción", "Cantidad", "Precio"],
      ...items.map((item) => [item.id, item.descripcion, item.cantidad, `${item.precio} €`]),
      [{ text: "TOTAL", colSpan: 3, bold: true }, "", "", `${total} €`],
    ])
      .headerRows(1)
      .layout({
        fillColor: (rowIndex) => (rowIndex === 0 ? "#f8f9fa" : null),
      })
      .margin([0, 0, 0, 20])
      .end
  );

  // Nota final
  pdf.add(
    new Txt("Gracias por su confianza. Este recibo es válido como comprobante de pago.")
      .italics()
      .fontSize(10)
      .alignment("center")
      .end
  );

  // Pie de página
  pdf.footer((currentPage, pageCount) =>
    new Txt(`Página ${currentPage} de ${pageCount}`)
      .fontSize(10)
      .alignment("center")
      .end
  );

  // Generar el PDF
  pdf.create().download(`recibo_${nombre}_${fecha}.pdf`);
};