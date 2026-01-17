#!/bin/bash

# Script para generar el Manual de Usuario de ION Inventory en múltiples formatos
# Requiere: pandoc, wkhtmltopdf

echo "🚀 Generando Manual de Usuario de ION Inventory..."
echo ""

# Directorio base
BASE_DIR="/Users/jonathanm/Documents/PERSONAL/Desarrollo Software/App-Inventario/ION"
cd "$BASE_DIR"

# 1. Generar PDF con Pandoc
echo "📄 Generando PDF..."
pandoc MANUAL_USUARIO_ION.md \
  -o "Manual_Usuario_ION_Inventory.pdf" \
  --pdf-engine=wkhtmltopdf \
  --toc \
  --toc-depth=2 \
  -V geometry:margin=1in \
  -V fontsize=11pt \
  -V documentclass=article \
  -V papersize=letter \
  --metadata title="Manual de Usuario - ION Inventory" \
  --metadata author="OutDeveloper" \
  --metadata date="$(date '+%B %Y')" \
  2>/dev/null

if [ $? -eq 0 ]; then
    echo "✅ PDF generado: Manual_Usuario_ION_Inventory.pdf"
else
    echo "⚠️  Error al generar PDF. Intentando método alternativo..."
    
    # Método alternativo: Generar HTML primero y luego PDF
    pandoc MANUAL_USUARIO_ION.md \
      -o temp_manual.html \
      --self-contained \
      --toc \
      --toc-depth=2 \
      -c https://cdn.jsdelivr.net/npm/[email protected]/dist/css/bootstrap.min.css
    
    if command -v wkhtmltopdf &> /dev/null; then
        wkhtmltopdf \
          --enable-local-file-access \
          --margin-top 20mm \
          --margin-bottom 20mm \
          --margin-left 15mm \
          --margin-right 15mm \
          temp_manual.html Manual_Usuario_ION_Inventory.pdf
        rm temp_manual.html
        echo "✅ PDF generado con método alternativo"
    else
        echo "❌ wkhtmltopdf no está instalado. Instálalo con: brew install wkhtmltopdf"
    fi
fi

# 2. Generar HTML interactivo
echo ""
echo "🌐 Generando versión HTML..."
pandoc MANUAL_USUARIO_ION.md \
  -o "Manual_Usuario_ION_Inventory.html" \
  --self-contained \
  --toc \
  --toc-depth=2 \
  --css=<(cat <<'EOF'
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background: #f5f5f5;
}
#TOC {
    background: white;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
h1, h2, h3 {
    color: #2c3e50;
}
h1 {
    border-bottom: 3px solid #3498db;
    padding-bottom: 10px;
}
h2 {
    border-bottom: 2px solid #ecf0f1;
    padding-bottom: 8px;
    margin-top: 40px;
}
img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin: 20px 0;
}
code {
    background: #ecf0f1;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
}
pre {
    background: #2c3e50;
    color: #ecf0f1;
    padding: 15px;
    border-radius: 5px;
    overflow-x: auto;
}
table {
    border-collapse: collapse;
    width: 100%;
    margin: 20px 0;
}
th, td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
}
th {
    background-color: #3498db;
    color: white;
}
tr:nth-child(even) {
    background-color: #f2f2f2;
}
EOF
)

if [ $? -eq 0 ]; then
    echo "✅ HTML generado: Manual_Usuario_ION_Inventory.html"
else
    echo "❌ Error al generar HTML"
fi

# 3. Generar versión DOCX (Word)
echo ""
echo "📝 Generando versión Word..."
pandoc MANUAL_USUARIO_ION.md \
  -o "Manual_Usuario_ION_Inventory.docx" \
  --toc \
  --toc-depth=2 \
  --reference-doc=<(cat <<'EOF'
EOF
)

if [ $? -eq 0 ]; then
    echo "✅ DOCX generado: Manual_Usuario_ION_Inventory.docx"
else
    echo "⚠️  No se pudo generar DOCX"
fi

echo ""
echo "✨ Proceso completado!"
echo ""
echo "📦 Archivos generados:"
ls -lh Manual_Usuario_ION_Inventory.* 2>/dev/null | awk '{print "   " $9 " (" $5 ")"}'
echo ""
echo "💡 Tip: Abre el archivo HTML en tu navegador para una versión interactiva"
