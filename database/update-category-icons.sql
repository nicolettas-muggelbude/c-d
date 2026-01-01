-- Kategorie-Icons hinzufügen
-- PC-Wittfoot UG

-- Icons für Kategorien (Emojis)
UPDATE categories SET icon = '💻' WHERE slug = 'notebooks-laptops';
UPDATE categories SET icon = '🖥️' WHERE slug = 'desktop-pcs';
UPDATE categories SET icon = '📱' WHERE slug = 'tablets-smartphones';
UPDATE categories SET icon = '🖨️' WHERE slug = 'drucker-scanner';
UPDATE categories SET icon = '⌨️' WHERE slug = 'peripherie';
UPDATE categories SET icon = '🔧' WHERE slug = 'zubehoer-ersatzteile';
UPDATE categories SET icon = '💾' WHERE slug = 'storage-speicher';
UPDATE categories SET icon = '📡' WHERE slug = 'netzwerk-wlan';
