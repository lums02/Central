-- Vérifier toutes les permissions existantes dans la base
SELECT name FROM permissions ORDER BY name;

-- Vérifier spécifiquement les nouvelles permissions
SELECT name FROM permissions WHERE name IN (
    'view_stocks', 'create_stocks', 'edit_stocks', 'delete_stocks', 'list_stocks',
    'view_sang', 'create_sang', 'edit_sang', 'delete_sang', 'list_sang',
    'view_prescriptions', 'create_prescriptions', 'edit_prescriptions', 'delete_prescriptions', 'list_prescriptions',
    'view_rendezvous', 'create_rendezvous', 'edit_rendezvous', 'delete_rendezvous', 'list_rendezvous'
) ORDER BY name;

-- Compter le total des permissions
SELECT COUNT(*) as 'Total permissions' FROM permissions;
