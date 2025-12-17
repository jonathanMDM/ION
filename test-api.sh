#!/bin/bash

# 🧪 Script de Prueba Rápida - ION Inventory API v2
# ================================================

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuración
API_URL="https://ion-app-120e60a9275c.herokuapp.com/api/v2"

echo -e "${BLUE}╔════════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║   🧪 ION Inventory API - Prueba Rápida       ║${NC}"
echo -e "${BLUE}╚════════════════════════════════════════════════╝${NC}"
echo ""

# Solicitar credenciales
read -p "📧 Email: " EMAIL
read -sp "🔒 Password: " PASSWORD
echo ""
echo ""

# 1. LOGIN
echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BLUE}🔐 1. Iniciando sesión...${NC}"
echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

LOGIN_RESPONSE=$(curl -s -X POST "$API_URL/auth/login" \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"$EMAIL\",\"password\":\"$PASSWORD\"}")

# Verificar si jq está instalado
if ! command -v jq &> /dev/null; then
    echo -e "${RED}⚠️  jq no está instalado. Instalando...${NC}"
    echo "Respuesta sin formato:"
    echo "$LOGIN_RESPONSE"
    echo ""
    echo "Para mejor visualización, instala jq:"
    echo "  macOS: brew install jq"
    echo "  Linux: sudo apt-get install jq"
    exit 1
fi

TOKEN=$(echo $LOGIN_RESPONSE | jq -r '.data.token')

if [ "$TOKEN" == "null" ] || [ -z "$TOKEN" ]; then
  echo -e "${RED}❌ Error en login${NC}"
  echo "$LOGIN_RESPONSE" | jq
  exit 1
fi

echo -e "${GREEN}✅ Login exitoso${NC}"
echo "Token: ${TOKEN:0:30}..."
echo "$LOGIN_RESPONSE" | jq '.data.user'

# 2. OBTENER USUARIO ACTUAL
echo ""
echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BLUE}👤 2. Obteniendo información del usuario...${NC}"
echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

USER_RESPONSE=$(curl -s -X GET "$API_URL/auth/user" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json")

echo "$USER_RESPONSE" | jq

# 3. LISTAR ACTIVOS
echo ""
echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BLUE}📦 3. Listando activos (primeros 5)...${NC}"
echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

ASSETS_RESPONSE=$(curl -s -X GET "$API_URL/assets?per_page=5" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json")

TOTAL_ASSETS=$(echo "$ASSETS_RESPONSE" | jq -r '.meta.total // 0')
echo -e "${GREEN}Total de activos: $TOTAL_ASSETS${NC}"
echo "$ASSETS_RESPONSE" | jq '.data[] | {id, name, code, status}'

# 4. LISTAR CATEGORÍAS
echo ""
echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BLUE}📁 4. Listando categorías...${NC}"
echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

CATEGORIES_RESPONSE=$(curl -s -X GET "$API_URL/categories?per_page=5" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json")

TOTAL_CATEGORIES=$(echo "$CATEGORIES_RESPONSE" | jq -r '.meta.total // 0')
echo -e "${GREEN}Total de categorías: $TOTAL_CATEGORIES${NC}"
echo "$CATEGORIES_RESPONSE" | jq '.data[] | {id, name, assets_count}'

# 5. LISTAR UBICACIONES
echo ""
echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BLUE}📍 5. Listando ubicaciones...${NC}"
echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

LOCATIONS_RESPONSE=$(curl -s -X GET "$API_URL/locations?per_page=5" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json")

TOTAL_LOCATIONS=$(echo "$LOCATIONS_RESPONSE" | jq -r '.meta.total // 0')
echo -e "${GREEN}Total de ubicaciones: $TOTAL_LOCATIONS${NC}"
echo "$LOCATIONS_RESPONSE" | jq '.data[] | {id, name, assets_count}'

# 6. LISTAR MANTENIMIENTOS
echo ""
echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BLUE}🔧 6. Listando mantenimientos (últimos 5)...${NC}"
echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

MAINTENANCES_RESPONSE=$(curl -s -X GET "$API_URL/maintenances?per_page=5" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json")

TOTAL_MAINTENANCES=$(echo "$MAINTENANCES_RESPONSE" | jq -r '.meta.total // 0')
echo -e "${GREEN}Total de mantenimientos: $TOTAL_MAINTENANCES${NC}"
echo "$MAINTENANCES_RESPONSE" | jq '.data[] | {id, type, status, scheduled_date}'

# 7. PRUEBA DE CREACIÓN (OPCIONAL)
echo ""
read -p "¿Quieres probar crear un activo de prueba? (s/n): " CREATE_TEST

if [ "$CREATE_TEST" == "s" ] || [ "$CREATE_TEST" == "S" ]; then
    echo ""
    echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo -e "${BLUE}➕ 7. Creando activo de prueba...${NC}"
    echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    
    # Obtener primera categoría disponible
    FIRST_CATEGORY_ID=$(echo "$CATEGORIES_RESPONSE" | jq -r '.data[0].id // 1')
    
    TIMESTAMP=$(date +%s)
    CREATE_RESPONSE=$(curl -s -X POST "$API_URL/assets" \
      -H "Authorization: Bearer $TOKEN" \
      -H "Content-Type: application/json" \
      -d "{
        \"name\": \"Activo de Prueba API\",
        \"code\": \"API-TEST-$TIMESTAMP\",
        \"category_id\": $FIRST_CATEGORY_ID,
        \"status\": \"available\",
        \"description\": \"Creado mediante prueba de API\"
      }")
    
    if echo "$CREATE_RESPONSE" | jq -e '.success' > /dev/null 2>&1; then
        echo -e "${GREEN}✅ Activo creado exitosamente${NC}"
        echo "$CREATE_RESPONSE" | jq '.data'
        
        CREATED_ID=$(echo "$CREATE_RESPONSE" | jq -r '.data.id')
        
        # Preguntar si quiere eliminarlo
        echo ""
        read -p "¿Quieres eliminar el activo de prueba? (s/n): " DELETE_TEST
        
        if [ "$DELETE_TEST" == "s" ] || [ "$DELETE_TEST" == "S" ]; then
            echo ""
            echo -e "${BLUE}🗑️  Eliminando activo de prueba...${NC}"
            
            DELETE_RESPONSE=$(curl -s -X DELETE "$API_URL/assets/$CREATED_ID" \
              -H "Authorization: Bearer $TOKEN" \
              -H "Accept: application/json")
            
            if echo "$DELETE_RESPONSE" | jq -e '.success' > /dev/null 2>&1; then
                echo -e "${GREEN}✅ Activo eliminado exitosamente${NC}"
            else
                echo -e "${RED}❌ Error al eliminar activo${NC}"
                echo "$DELETE_RESPONSE" | jq
            fi
        fi
    else
        echo -e "${RED}❌ Error al crear activo${NC}"
        echo "$CREATE_RESPONSE" | jq
    fi
fi

# 8. LOGOUT
echo ""
echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BLUE}🚪 8. Cerrando sesión...${NC}"
echo -e "${YELLOW}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

LOGOUT_RESPONSE=$(curl -s -X POST "$API_URL/auth/logout" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json")

echo "$LOGOUT_RESPONSE" | jq

# RESUMEN
echo ""
echo -e "${GREEN}╔════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║          ✅ Pruebas Completadas               ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════════════╝${NC}"
echo ""
echo -e "${BLUE}📊 Resumen:${NC}"
echo -e "  • Activos: $TOTAL_ASSETS"
echo -e "  • Categorías: $TOTAL_CATEGORIES"
echo -e "  • Ubicaciones: $TOTAL_LOCATIONS"
echo -e "  • Mantenimientos: $TOTAL_MAINTENANCES"
echo ""
echo -e "${YELLOW}📚 Para más información, consulta:${NC}"
echo -e "  • API_DOCUMENTATION.md - Documentación completa"
echo -e "  • API_QUICK_REFERENCE.md - Referencia rápida"
echo -e "  • API_TESTING_GUIDE.md - Guía de pruebas detallada"
echo ""
