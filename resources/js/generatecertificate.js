const fs = require('fs');
const moment = require('moment');
const PDFDocument = require('pdfkit');

const doc = new PDFDocument({ size: 'A4', layouts: 'landscape' });

// import poppins font
doc.registerFont('Poppins-regular', 'public/fonts/Poppins-Regular.ttf');
doc.registerFont('Poppins-bold', 'public/fonts/Poppins-ExtraBold.ttf');

const generateCertificate = (name, course, date) => {
    doc.pipe(fs.createWriteStream(`public/certificate/${name}.pdf`));
    doc.image('public/img/sertifikat.png', 0, 0, { width: 842 });
    doc.fontSize(40);
    doc.font('Poppins-bold').fontSize(40).text(name, 420, 400, { align: 'center' });
    doc.text(course, 420, 450, { align: 'center' });
    doc.fontSize(15);
    doc.font('Poppins-regular').fontSize(15).text(date, 420, 500, { align: 'center' });

    doc.end();
}

module.exports = generateCertificate;
