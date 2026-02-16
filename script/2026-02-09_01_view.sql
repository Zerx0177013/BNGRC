--Vu pour voir le nombre d'user--
CREATE OR REPLACE  VIEW V_COUNT_USERS AS 
SELECT 
    COUNT(*) AS total_users
FROM 
    user;

