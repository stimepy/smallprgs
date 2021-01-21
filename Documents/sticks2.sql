-- insert purchase into crypto
insert into crypto_purc_sold(port_id, stock_id,	tran_stock_id, action_date, 
	 num_shares, price_per_share, tran_price_per_to_dol, initial_Fee_in_dol, Fee_in_Stock_id, total_add_fees,
	 act_id, Notes)
SELECT 14 port_id,
	 stock_id, 	
	 52 tran_stock_id, 
	 STR_TO_DATE('11-01-2020','%m-%d-%Y') action_date, 
	 .123521 num_shares, 
	 2.18 price_per_share, 
	 1 tran_price_per_to_dol, 
	 0 initial_Fee_in_dol, 
	 NULL Fee_in_Stock_id, 
	 0 total_add_fees,
	 9 act_id, 
	 NULL Notes
	 FROM stock_desc
	 WHERE ticker = '$USDC'	 
	 ;


-- New Crypto
insert into stable_share_num(port_id, stock_id, shares, Create_dttm, last_updated_dttm)
select port_id, sd.stock_id, sum(num_shares), now(),now()
from stock_desc sd
left join crypto_purc_sold sps
on(sd.stock_id = sps.stock_id)
where ticker = '$EOS'
group by port_id, sd.stock_id, now(),now();



-- or  adding to stable stocks - no splits
update stable_share_num  a
Join (
		SELECT sum(case when act_id in(1,9,18,21) then num_shares when act_id IN(2,20) then -num_shares end) shares
		, port_id
		,stock_id
	FROM crypto_purc_sold
	WHERE STOCK_ID in (select stock_id from stock_desc where ticker in ('$BTC'))
	-- and port_id =
group by port_id, stock_id
)b
on (a.stock_id = b.Stock_id 
	and a.port_id = b.port_id)
set a.shares = b.shares;



insert into tranactions(port_id, act_id, cash, date_of_transaction, temp_id, credit_debit)
select port_id, act_id, -- port and act id
case when act_id = 1 then -1 else 1 end*(((price_per_share*tran_price_per_to_dol)*num_shares)+initial_Fee_in_dol+total_add_fees), -- total cost
action_date,
purc_sold_id, -- purc_sold_id
0 credit_debit
from crypto_purc_sold 
where trans_id is null;




update crypto_purc_sold as spc
join tranactions as t on temp_id = purc_sold_id
 set spc.trans_id = t.trans_id
 where t.temp_id is not null;

  update tranactions set temp_id = null where temp_id is not null;
 -- End Purchase
  
  
 
  
  insert into tranactions(port_id, act_id, cash, date_of_transaction, notes)
values(15, 17, 1.96, STR_TO_DATE('12-31-2015','%m-%d-%Y'), 'Zero Sum cash gain/loss since it was a straight exchange.');



-- More automated insert from temp
insert into crypto_purc_sold(port_id, stock_id,	tran_stock_id, action_date, num_shares, price_per_share, tran_price_per_to_dol, initial_Fee_in_dol, Fee_in_Stock_id, total_add_fees, 	Value_at_time_of, act_id, Notes)
	 SELECT 14 port_id, 
	 	stock_id,
		 52 tran_stock_id, 
		DTIMESTAMP action_date, 
	 	Quantity num_shares, 
	 	USD_Spot_Price price_per_share, 
		1 tran_price_per_to_dol,
		0 initial_Fee_in_dol, 
		NULL Fee_in_Stock_id, 
		0  total_add_fees,
		case when asset <> '$USDC' then usd_total ELSE Quantity END  Value_at_time_of,
	 	case 
		 	when Transaction_Type = 'Rewards Income' then 9
		 	when Transaction_Type = 'Coinbase Earn' then 1
		 	end  act_id, 
		case 
		 	when Transaction_Type = 'Rewards Income' then Transaction_Type
		 	when Transaction_Type = 'Coinbase Earn' then 'Coinbase Promotion'
		 	end 	 Notes 
FROM (
	SELECT str_to_date(substr(DTIMESTAMP, 1, 10),'%Y-%m-%d') DTIMESTAMP, Transaction_Type, CONCAT('$',Asset) asset, Quantity, USD_Spot_Price, USD_Subtotal, USD_Total, USD_Fees, Notes
	FROM cb_temp
	-- WHERE str_to_date(substr(DTIMESTAMP, 1, 10),'%Y-%m-%d') >  STR_TO_DATE('10-31-2020','%m-%d-%Y')
)cb_temp
LEFT JOIN stock_desc
ON(asset = ticker)
--WHERE asset <> '$BTC'
;


insert into stable_share_num(port_id, stock_id, shares, Create_dttm, last_updated_dttm)
select port_id, sd.stock_id, sum(num_shares), now(),now()
from stock_desc sd
left join crypto_purc_sold sps
on(sd.stock_id = sps.stock_id)
where ticker = '$EOS'
group by port_id, sd.stock_id, now(),now();



-- or  adding to stable stocks - no splits
update stable_share_num  a
Join (
		SELECT sum(case when act_id in(1,9,18,21) then num_shares when act_id IN(2,20) then -num_shares end) shares
		, port_id
		,stock_id
	FROM crypto_purc_sold
	WHERE STOCK_ID in (select stock_id from stock_desc where ticker in ('$BTC'))
	-- and port_id =
group by port_id, stock_id
)b
on (a.stock_id = b.Stock_id 
	and a.port_id = b.port_id)
set a.shares = b.shares;




insert into tranactions(port_id, act_id, cash, date_of_transaction, temp_id, credit_debit)
select port_id, act_id, -- port and act id
-- case when act_id = 1 then -1 else 1 end*(((price_per_share*tran_price_per_to_dol)*num_shares)+initial_Fee_in_dol+total_add_fees), -- total cost
0, 
action_date,
purc_sold_id, -- purc_sold_id
0 credit_debit
from crypto_purc_sold 
where trans_id is null;




update crypto_purc_sold as spc
join tranactions as t on temp_id = purc_sold_id
 set spc.trans_id = t.trans_id
 where t.temp_id is not null;

  update tranactions set temp_id = null where temp_id is not null;