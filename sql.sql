SELECT p0_.id AS id_0
FROM product p0_
LEFT JOIN product_set p1_ ON p0_.id = p1_.product_id
LEFT JOIN product2option p2_ ON p1_.id = p2_.product_set_id
LEFT JOIN attribute_value a3_ ON p2_.attribute_value_id = a3_.id
WHERE a3_.id IN (1,3,7) GROUP BY p1_.id





SELECT p0_.id AS id_0,
  (SELECT COUNT(a1_.id) AS dctrn__1,
  FROM product p2_
  LEFT JOIN product2attribute p3_ ON (p2_.id = p3_.product_id)
  LEFT JOIN attribute_value a1_ ON (p3_.attribute_value_id = a1_.id)
  WHERE p2_.category_id = 1 AND a1_.id IN (1, 3, 7)
  GROUP BY p2_.id) AS sclr_1
FROM product p0_
LEFT JOIN product_set p4_ ON p0_.id = p4_.product_id
LEFT JOIN product2option p5_ ON p4_.id = p5_.product_set_id
LEFT JOIN attribute_value a6_ ON p5_.attribute_value_id = a6_.id
WHERE p0_.category_id = 1 AND (a6_.id IN (1,3,7))
GROUP BY p4_.id
HAVING COUNT(p0_.id) = ?









SELECT p0_.id AS id_0, count(a3_.id), count(a5_.id)
FROM product p0_
LEFT JOIN product_set p1_ ON p0_.id = p1_.product_id
LEFT JOIN product2option p2_ ON p1_.id = p2_.product_set_id
LEFT JOIN attribute_value a3_ ON p2_.attribute_value_id = a3_.id
LEFT JOIN product2attribute p4_ ON p0_.id = p4_.product_id
LEFT JOIN attribute_value a5_ ON p4_.attribute_value_id = a5_.id
WHERE p0_.category_id = 1
AND (a3_.id IN (1,3,7))
GROUP BY p1_.id, a5_.id
HAVING COUNT(p0_.id) = 1



SELECT p0_.id AS id_0
FROM product p0_
 JOIN product_set p1_ ON p0_.id = p1_.product_id
 JOIN product2option p2_ ON p1_.id = p2_.product_set_id
 JOIN attribute_value a3_ ON p2_.attribute_value_id = a3_.id
WHERE p0_.category_id = 1 AND (a3_.id IN (1,3,7))
GROUP BY p1_.id
-- HAVING COUNT(a5_.id)+COUNT(a3_.id) = 2




-- SELECT p0_.id AS id_0, count(a3_.id)

SELECT p0_.id AS id_0, count(a3_.id)
FROM product p0_
  JOIN product_set p1_ ON p0_.id = p1_.product_id
  JOIN product2option p2_ ON p1_.id = p2_.product_set_id
  JOIN attribute_value a3_ ON p2_.attribute_value_id = a3_.id
  JOIN product2attribute p4_ ON p0_.id = p4_.product_id and a3_.id = p4_.attribute_value_id
 -- JOIN attribute_value a5_ ON p4_.attribute_value_id = a5_.id
WHERE p0_.category_id = 1 AND (a3_.id IN (1,3,7))
GROUP BY p1_.id
-- HAVING COUNT(p0_.id) = ?
