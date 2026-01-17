#!/usr/bin/env python3
"""
Generador de Manual de Usuario ION Inventory
Convierte el Markdown a HTML con estilos profesionales
"""

import markdown
import os
from pathlib import Path

# Configuración
BASE_DIR = Path(__file__).parent
INPUT_FILE = BASE_DIR / "MANUAL_USUARIO_ION.md"
OUTPUT_HTML = BASE_DIR / "Manual_Usuario_ION_Inventory.html"

# Leer el archivo Markdown
with open(INPUT_FILE, 'r', encoding='utf-8') as f:
    md_content = f.read()

# Convertir Markdown a HTML
html_content = markdown.markdown(
    md_content,
    extensions=['tables', 'fenced_code', 'toc', 'attr_list']
)

# Template HTML con estilos
html_template = f"""<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual de Usuario - ION Inventory</title>
    <style>
        * {{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }}
        
        body {{
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.8;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }}
        
        .container {{
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }}
        
        header {{
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 40px;
            text-align: center;
        }}
        
        header h1 {{
            font-size: 3em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }}
        
        header p {{
            font-size: 1.2em;
            opacity: 0.9;
        }}
        
        .content {{
            padding: 40px;
        }}
        
        h1, h2, h3, h4 {{
            color: #2c3e50;
            margin-top: 30px;
            margin-bottom: 15px;
        }}
        
        h1 {{
            font-size: 2.5em;
            border-bottom: 4px solid #667eea;
            padding-bottom: 15px;
        }}
        
        h2 {{
            font-size: 2em;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
            margin-top: 50px;
        }}
        
        h3 {{
            font-size: 1.5em;
            color: #667eea;
        }}
        
        p {{
            margin: 15px 0;
            text-align: justify;
        }}
        
        ul, ol {{
            margin: 15px 0 15px 30px;
        }}
        
        li {{
            margin: 8px 0;
        }}
        
        img {{
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
            margin: 30px 0;
            display: block;
            border: 1px solid #e0e0e0;
        }}
        
        code {{
            background: #f4f4f4;
            padding: 3px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #e74c3c;
            font-size: 0.9em;
        }}
        
        pre {{
            background: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
            margin: 20px 0;
        }}
        
        pre code {{
            background: none;
            color: inherit;
            padding: 0;
        }}
        
        table {{
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }}
        
        th, td {{
            border: 1px solid #ddd;
            padding: 15px;
            text-align: left;
        }}
        
        th {{
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: bold;
        }}
        
        tr:nth-child(even) {{
            background-color: #f9f9f9;
        }}
        
        tr:hover {{
            background-color: #f0f0f0;
        }}
        
        blockquote {{
            border-left: 4px solid #667eea;
            padding-left: 20px;
            margin: 20px 0;
            font-style: italic;
            color: #555;
            background: #f9f9f9;
            padding: 15px 20px;
            border-radius: 4px;
        }}
        
        hr {{
            border: none;
            border-top: 2px solid #e0e0e0;
            margin: 40px 0;
        }}
        
        .toc {{
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin: 30px 0;
            border-left: 4px solid #667eea;
        }}
        
        .toc h2 {{
            margin-top: 0;
            color: #667eea;
        }}
        
        .toc ul {{
            list-style: none;
            margin-left: 0;
        }}
        
        .toc li {{
            margin: 10px 0;
        }}
        
        .toc a {{
            color: #2c3e50;
            text-decoration: none;
            transition: color 0.3s;
        }}
        
        .toc a:hover {{
            color: #667eea;
        }}
        
        footer {{
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 30px;
            margin-top: 50px;
        }}
        
        @media print {{
            body {{
                background: white;
                padding: 0;
            }}
            
            .container {{
                box-shadow: none;
            }}
            
            img {{
                page-break-inside: avoid;
            }}
        }}
        
        @media (max-width: 768px) {{
            header h1 {{
                font-size: 2em;
            }}
            
            .content {{
                padding: 20px;
            }}
            
            h1 {{
                font-size: 1.8em;
            }}
            
            h2 {{
                font-size: 1.5em;
            }}
        }}
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>📘 Manual de Usuario</h1>
            <p>ION Inventory - Sistema de Gestión de Activos</p>
            <p style="font-size: 0.9em; margin-top: 10px;">Versión 1.1.0 | OutDeveloper © 2026</p>
        </header>
        
        <div class="content">
            {html_content}
        </div>
        
        <footer>
            <p><strong>OutDeveloper</strong></p>
            <p>📧 soporte@outdeveloper.com | 🌐 https://outdeveloper.com</p>
            <p style="margin-top: 15px; font-size: 0.9em;">© 2026 OutDeveloper. Todos los derechos reservados.</p>
        </footer>
    </div>
</body>
</html>"""

# Guardar el archivo HTML
with open(OUTPUT_HTML, 'w', encoding='utf-8') as f:
    f.write(html_template)

print(f"✅ Manual HTML generado exitosamente: {OUTPUT_HTML}")
print(f"📄 Tamaño: {OUTPUT_HTML.stat().st_size / 1024:.2f} KB")
print(f"\n💡 Abre el archivo en tu navegador para verlo")
print(f"🖨️  Usa Ctrl+P (Cmd+P en Mac) para imprimir o guardar como PDF")
