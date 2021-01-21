-- For porfolio 5   Update all numbers!

insert into tranactions(port_id, act_id, cash, date_of_transaction, notes, credit_debit)
values(5, 6, 325.42, STR_TO_DATE('01-17-2020','%m-%d-%Y'), null, 1);



insert into stock_purc_sold(port_id, stock_id, action_date, num_shares, price_per_share, initial_Fee, total_add_fees, act_id, Notes)
select 5, -- port
 stock_id,  -- stock_id
STR_TO_DATE('01-03-2020','%m-%d-%Y'), -- date
0.654, -- # of shares
248.79204893 , -- price
0,  -- basic fee
0,  -- addition fees
1,  -- action
NULL -- Notes
from stock_desc 
where ticker = 'VFIAX'
	union all
select 5, -- port
 stock_id,  -- stock_id
STR_TO_DATE('01-03-2020','%m-%d-%Y'), -- date
3.467, -- # of shares
23.46697433
 , -- price
0,  -- basic fee
0,  -- addition fees
1,  -- action
NULL -- Notes
from stock_desc 
where ticker = '@0001'
	union all
select 5, -- port
 stock_id,  -- stock_id
STR_TO_DATE('01-03-2020','%m-%d-%Y'), -- date
0.422, -- # of shares
192.77251185, -- price
0,  -- basic fee
0,  -- addition fees
1,  -- action
NULL -- Notes
from stock_desc 
where ticker = 'VIMAX';


update stable_share_num  a
Join (
		select sum(case when act_id = 1 then num_shares when act_id = 2 then -num_shares end) shares
		, port_id
		,stock_id
	FROM stock_purc_sold
	WHERE STOCK_ID in (select stock_id from stock_desc where ticker in ('@0001', 'VFIAX', 'VIMAX'))
	-- and port_id =
	group by port_id, stock_id
)b
on (a.stock_id = b.Stock_id 
	and a.port_id = b.port_id)
set a.shares = b.shares;


insert into tranactions(port_id, act_id, cash, date_of_transaction, temp_id, credit_debit)
select port_id, act_id, -- port and act id
case when act_id = 1 then -1 else 1 end*((price_per_share*num_shares)+initial_Fee+total_add_fees), -- total cost
action_date,
purc_sold_id -- purc_sold_id
,0
from stock_purc_sold 
where trans_id is null;



update stock_purc_sold as spc
join tranactions as t on temp_id = purc_sold_id
 set spc.trans_id = t.trans_id
 where t.temp_id is not null;

  update tranactions set temp_id = null where temp_id is not null;
-- End stocks buy.




  
  
  
  
   -- Fee
  insert into tranactions(port_id, act_id, cash, date_of_transaction, credit_debit, notes)
values(5, 11, 83.01, STR_TO_DATE('01-18-2020','%m-%d-%Y'), 0, null);
  
  
  
  
  
-- For porfolio 6 Monthly   Update all numbers!

insert into div_record(port_id, stock_id, payment_num, announcement_date, ex_div_date, record_date, pay_date, num_stocks, div_amount)
select ssn.port_id, ssn.stock_id, pay.mxpmt, 
STR_TO_DATE('5-22-2017','%m-%d-%Y') announcement, 
STR_TO_DATE('5-24-2017','%m-%d-%Y') ex_div_date, 
STR_TO_DATE('5-26-2017','%m-%d-%Y') record_date, 
STR_TO_DATE('5-31-2017','%m-%d-%Y') pay_date, 
ssn.shares, <DivaMOUNT>	
from stock_desc sd
inner join stable_share_num ssn
on(sd.stock_id = ssn.stock_id)
left join (
	select max(payment_num)+1 mxpmt, stock_id, port_id
	from div_record d
	where stock_id in (select stock_id 
							from stock_desc sd2
							)
	and pay_date = (select max(pay_date) from div_record sd3
						where sd3.stock_id = d.stock_id
						)
	group by stock_id, port_id
)pay
on(ssn.stock_id = pay.stock_id
and ssn.port_id = pay.port_id)
where sd.ticker = 'PRTIX'



insert into stock_purc_sold(port_id, stock_id, action_date, num_shares, price_per_share, initial_Fee, total_add_fees, act_id, Notes)
select 6, -- port
 stock_id,  -- stock_id
STR_TO_DATE('05-31-2017','%m-%d-%Y'), -- date
0.606, -- # of shares
5.79, -- price
0,  -- basic fee
0,  -- addition fees
1,  -- action
NULL --Notes
from stock_desc 
where ticker = 'PRTIX';



update stable_share_num  a
Join (
		select sum(case when act_id = 1 then num_shares when act_id = 2 then -num_shares end) shares
		, port_id
		,stock_id
	FROM stock_purc_sold
	WHERE STOCK_ID in (select stock_id from stock_desc where ticker in ('PRTIX'))
	-- and port_id =
	group by port_id, stock_id
)b
on (a.stock_id = b.Stock_id 
	and a.port_id = b.port_id)
set a.shares = b.shares;



insert into tranactions(port_id, act_id, cash, date_of_transaction, temp_id)
select port_id, act_id, -- port and act id
case when act_id = 1 then -1 else 1 end*((price_per_share*num_shares)+initial_Fee+total_add_fees), -- total cost
action_date,
purc_sold_id -- purc_sold_id
from stock_purc_sold 
where action_date = STR_TO_DATE('4-18-2017','%m-%d-%Y');



update stock_purc_sold as spc
join tranactions as t on temp_id = purc_sold_id
 set spc.trans_id = t.trans_id
 where t.temp_id is not null;

  update tranactions set temp_id = null where temp_id is not null;
  
  
  
  
  
  -- Port 20
  
  
  
insert into tranactions(port_id, act_id, cash, date_of_transaction, notes, credit_debit)
values(20, 6, 325.42, STR_TO_DATE('06-22-2020','%m-%d-%Y'), null, 1);



insert into stock_purc_sold(port_id, stock_id, action_date, num_shares, price_per_share, initial_Fee, total_add_fees, act_id, Notes)
select 20, -- port
 stock_id,  -- stock_id
STR_TO_DATE('06-22-2020','%m-%d-%Y'), -- date
0.654, -- # of shares
248.79204893 , -- price
0,  -- basic fee
0,  -- addition fees
1,  -- action
NULL -- Notes
from stock_desc 
where ticker = 'VINIX'
	union all
select 20, -- port
 stock_id,  -- stock_id
STR_TO_DATE('06-22-2020','%m-%d-%Y'), -- date
3.467, -- # of shares
23.46697433
 , -- price
0,  -- basic fee
0,  -- addition fees
1,  -- action
NULL -- Notes
from stock_desc 
where ticker = 'VBTLX'
	union all
select 20, -- port
 stock_id,  -- stock_id
STR_TO_DATE('06-22-2020','%m-%d-%Y'), -- date
0.422, -- # of shares
192.77251185, -- price
0,  -- basic fee
0,  -- addition fees
1,  -- action
NULL -- Notes
from stock_desc 
where ticker = 'VEXAX';


update stable_share_num  a
Join (
		select sum(case when act_id = 1 then num_shares when act_id = 2 then -num_shares end) shares
		, port_id
		,stock_id
	FROM stock_purc_sold
	WHERE STOCK_ID in (select stock_id from stock_desc where ticker in ('VEXAX', 'VINIX', 'VBTLX'))
	-- and port_id =
	group by port_id, stock_id
)b
on (a.stock_id = b.Stock_id 
	and a.port_id = b.port_id)
set a.shares = b.shares;


insert into tranactions(port_id, act_id, cash, date_of_transaction, temp_id, credit_debit)
select port_id, act_id, -- port and act id
case when act_id = 1 then -1 else 1 end*((price_per_share*num_shares)+initial_Fee+total_add_fees), -- total cost
action_date,
purc_sold_id -- purc_sold_id
,0
from stock_purc_sold 
where trans_id is null;



update stock_purc_sold as spc
join tranactions as t on temp_id = purc_sold_id
 set spc.trans_id = t.trans_id
 where t.temp_id is not null;

  update tranactions set temp_id = null where temp_id is not null;
-- End stocks buy.