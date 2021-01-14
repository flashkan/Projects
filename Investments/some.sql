SELECT * 
FROM actions ac 
join assets ass
join currency c 
join operations o
on ac.asset_id = ass.id
and ac.currency_id = c.id
and ac.operation_id = o.id;



SELECT ass.id, ass.name, ass.ticker, ass.url_img, ac.count, ac.price, ac.commis, ac.date, c.name currency, o.name operation
FROM actions ac 
join assets ass
join currency c 
join operations o
on ac.asset_id = ass.id
and ac.currency_id = c.id
and ac.operation_id = o.id;