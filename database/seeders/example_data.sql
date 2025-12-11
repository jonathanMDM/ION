-- Categorías y Subcategorías para company_id = 34

-- Tecnología
INSERT INTO categories (name, company_id, created_at, updated_at) VALUES ('Tecnología', 34, NOW(), NOW()) ON CONFLICT DO NOTHING;
INSERT INTO subcategories (name, category_id, company_id, created_at, updated_at) 
SELECT 'Computadoras', id, 34, NOW(), NOW() FROM categories WHERE name = 'Tecnología' AND company_id = 34 ON CONFLICT DO NOTHING;
INSERT INTO subcategories (name, category_id, company_id, created_at, updated_at) 
SELECT 'Servidores', id, 34, NOW(), NOW() FROM categories WHERE name = 'Tecnología' AND company_id = 34 ON CONFLICT DO NOTHING;
INSERT INTO subcategories (name, category_id, company_id, created_at, updated_at) 
SELECT 'Redes', id, 34, NOW(), NOW() FROM categories WHERE name = 'Tecnología' AND company_id = 34 ON CONFLICT DO NOTHING;
INSERT INTO subcategories (name, category_id, company_id, created_at, updated_at) 
SELECT 'Periféricos', id, 34, NOW(), NOW() FROM categories WHERE name = 'Tecnología' AND company_id = 34 ON CONFLICT DO NOTHING;
INSERT INTO subcategories (name, category_id, company_id, created_at, updated_at) 
SELECT 'Telefonía', id, 34, NOW(), NOW() FROM categories WHERE name = 'Tecnología' AND company_id = 34 ON CONFLICT DO NOTHING;

-- Mobiliario
INSERT INTO categories (name, company_id, created_at, updated_at) VALUES ('Mobiliario', 34, NOW(), NOW()) ON CONFLICT DO NOTHING;
INSERT INTO subcategories (name, category_id, company_id, created_at, updated_at) 
SELECT 'Escritorios', id, 34, NOW(), NOW() FROM categories WHERE name = 'Mobiliario' AND company_id = 34 ON CONFLICT DO NOTHING;
INSERT INTO subcategories (name, category_id, company_id, created_at, updated_at) 
SELECT 'Sillas', id, 34, NOW(), NOW() FROM categories WHERE name = 'Mobiliario' AND company_id = 34 ON CONFLICT DO NOTHING;
INSERT INTO subcategories (name, category_id, company_id, created_at, updated_at) 
SELECT 'Archivadores', id, 34, NOW(), NOW() FROM categories WHERE name = 'Mobiliario' AND company_id = 34 ON CONFLICT DO NOTHING;
INSERT INTO subcategories (name, category_id, company_id, created_at, updated_at) 
SELECT 'Estanterías', id, 34, NOW(), NOW() FROM categories WHERE name = 'Mobiliario' AND company_id = 34 ON CONFLICT DO NOTHING;

-- Vehículos
INSERT INTO categories (name, company_id, created_at, updated_at) VALUES ('Vehículos', 34, NOW(), NOW()) ON CONFLICT DO NOTHING;
INSERT INTO subcategories (name, category_id, company_id, created_at, updated_at) 
SELECT 'Automóviles', id, 34, NOW(), NOW() FROM categories WHERE name = 'Vehículos' AND company_id = 34 ON CONFLICT DO NOTHING;
INSERT INTO subcategories (name, category_id, company_id, created_at, updated_at) 
SELECT 'Camionetas', id, 34, NOW(), NOW() FROM categories WHERE name = 'Vehículos' AND company_id = 34 ON CONFLICT DO NOTHING;
INSERT INTO subcategories (name, category_id, company_id, created_at, updated_at) 
SELECT 'Motocicletas', id, 34, NOW(), NOW() FROM categories WHERE name = 'Vehículos' AND company_id = 34 ON CONFLICT DO NOTHING;

-- Equipos de Oficina
INSERT INTO categories (name, company_id, created_at, updated_at) VALUES ('Equipos de Oficina', 34, NOW(), NOW()) ON CONFLICT DO NOTHING;
INSERT INTO subcategories (name, category_id, company_id, created_at, updated_at) 
SELECT 'Impresoras', id, 34, NOW(), NOW() FROM categories WHERE name = 'Equipos de Oficina' AND company_id = 34 ON CONFLICT DO NOTHING;
INSERT INTO subcategories (name, category_id, company_id, created_at, updated_at) 
SELECT 'Fotocopiadoras', id, 34, NOW(), NOW() FROM categories WHERE name = 'Equipos de Oficina' AND company_id = 34 ON CONFLICT DO NOTHING;
INSERT INTO subcategories (name, category_id, company_id, created_at, updated_at) 
SELECT 'Proyectores', id, 34, NOW(), NOW() FROM categories WHERE name = 'Equipos de Oficina' AND company_id = 34 ON CONFLICT DO NOTHING;

-- Herramientas
INSERT INTO categories (name, company_id, created_at, updated_at) VALUES ('Herramientas', 34, NOW(), NOW()) ON CONFLICT DO NOTHING;
INSERT INTO subcategories (name, category_id, company_id, created_at, updated_at) 
SELECT 'Herramientas Eléctricas', id, 34, NOW(), NOW() FROM categories WHERE name = 'Herramientas' AND company_id = 34 ON CONFLICT DO NOTHING;
INSERT INTO subcategories (name, category_id, company_id, created_at, updated_at) 
SELECT 'Herramientas Manuales', id, 34, NOW(), NOW() FROM categories WHERE name = 'Herramientas' AND company_id = 34 ON CONFLICT DO NOTHING;

-- Electrodomésticos
INSERT INTO categories (name, company_id, created_at, updated_at) VALUES ('Electrodomésticos', 34, NOW(), NOW()) ON CONFLICT DO NOTHING;
INSERT INTO subcategories (name, category_id, company_id, created_at, updated_at) 
SELECT 'Refrigeradores', id, 34, NOW(), NOW() FROM categories WHERE name = 'Electrodomésticos' AND company_id = 34 ON CONFLICT DO NOTHING;
INSERT INTO subcategories (name, category_id, company_id, created_at, updated_at) 
SELECT 'Microondas', id, 34, NOW(), NOW() FROM categories WHERE name = 'Electrodomésticos' AND company_id = 34 ON CONFLICT DO NOTHING;
INSERT INTO subcategories (name, category_id, company_id, created_at, updated_at) 
SELECT 'Cafeteras', id, 34, NOW(), NOW() FROM categories WHERE name = 'Electrodomésticos' AND company_id = 34 ON CONFLICT DO NOTHING;
