-- div gotten
inbsert into tranactions(port_id, act_id, cash, date_of_transaction, temp_id, credit_debit)
select port_id, 8,  round((num_stocks* div_amount),2), pay_date, div_rec_id, 1
from div_record
where pay_date <= cast(now() as date)
and div_record.trans_id is null
and div_record.div_amount is not NULL;


update div_record as spc
join tranactions as t on temp_id = div_rec_id
 set spc.trans_id = t.trans_id
 where t.temp_id is not null;
 
  update tranactions set temp_id = null where temp_id is not null;
  -- end div gotten
  

  
-- Information on individual stock  
SELECT sd.stock_id,
sd.ticker,
sd.company_name,
sd.current_Quarterly_dividend,
sd.payment_per_year,
sd.website,
COUNT(port_id),
SUM(ssn.shares) Shares,
case when ssn.currently_owned = 1 then 'Owned' ELSE NULL END Owned,
sum(ssn.Price_per_share)/COUNT(port_id) Price_per_share,
ssn.last_updated_dttm,
ppsc.Price_per_share calulated_price_per_share
FROM stock_desc sd
INNER JOIN stable_share_num ssn
USING(stock_id)
LEFT JOIN(
	SELECT stock_id, (price_per_share+coalesce(shares_sold, 0))/num_shares Price_per_share, shares_sold, price_per_share/num_shares, num_shares
	FROM(
		 SELECT SUM(case when spc.act_id IN(2,20) then -1 ELSE 1 END * spc.num_shares) num_shares, 
		 SUM(	case when spc.act_id IN(2,20) then 0 ELSE 1 END * spc.price_per_share*spc.num_shares) price_per_share,
		 SUM(	case when spc.act_id IN(2,20) then 1 ELSE 0 END * shares_sold) shares_sold
		 ,spc.stock_id
		 FROM stock_purc_sold spc
		 left JOIN (
			 SELECT stock_id, port_id,  sum(spc2.price_per_share * sl.share_num * -1) shares_sold
			 From stocks_sale_lot sl
			 LEFT JOIN stock_purc_sold spc2
				ON(sl.purc_sold_id_buy = spc2.purc_sold_id)
				GROUP BY  stock_id, port_id
				having sum(spc2.price_per_share * sl.share_num * -1) <> 0
		)salelot
		ON(salelot.stock_id = spc.stock_id AND salelot.port_id = spc.port_id)
	  -- WHERE spc.stock_id = 40 AND spc.port_id = 20
		GROUP BY spc.stock_id
	)c
)ppsc
on(ppsc.stock_id = sd.stock_id)
 WHERE ticker = 'SCHD';




select ticker, 
	company_name, 
	shares, 
	round(price_per_share,4) pps, 
	total total_purchase,  
	total_wfee total_purchase_pluscomm,  
	concat(cast(round((shares/tot_shares)*100,4) as char),'%') "% by stock", 
	concat(cast(round((total/tot_price)*100,4) as char),'%') "% by value no fees",  
	annual "Annual Dividend", 
	shares * annual "Annual Tot Div" 
	, concat(cast(round((annual/price_per_share)*100,4) as char),'%')  "Yield on Cost"
-- 	, avg((annual/(total/shares))*100)
	, payment_per_year "Payments Per Year"
from(
	select ticker, company_name, round(ssn.shares,4) shares, 
		round(sum(case when act_id = 2 then -1*((num_shares*spc.price_per_share))
		else ((num_shares*spc.price_per_share)) end),2) total,
		round(sum(case when act_id = 2 then -1*((num_shares*spc.price_per_share)+(initial_Fee+total_add_fees))
		else ((num_shares*spc.price_per_share)+(initial_Fee+total_add_fees)) end),2) total_wfee,
		current_Quarterly_dividend quarterly, 
		current_Quarterly_dividend*payment_per_year annual, 
		case when payment_per_year is null then 0 else payment_per_year end payment_per_year,
		tot_shares, 
		tot_price,
		spc.price_per_share
	from stock_desc sd
	inner join stable_share_num ssn
	 using(stock_id)
	inner join stock_purc_sold spc
	 on(sd.stock_id = spc.stock_id
		and ssn.port_id = spc.port_id)
	left join(
		select round(sum(ssn.shares)) tot_shares, 
			tot_price, a.port_id
		from stable_share_num ssn
		inner join (
			select round(sum(case when act_id in(2,20) then -1*((num_shares*spc.price_per_share))
								else ((num_shares*spc.price_per_share)) 
						end),2) tot_price, ssn.port_id
			from stable_share_num ssn
			inner join stock_purc_sold spc
			 on(ssn.stock_id = spc.stock_id
				and ssn.port_id = spc.port_id)
			where ssn.port_id = 7
		)a
		on(1=1)
	where ssn.port_id = a.port_id
	group by tot_price
	) tots
	on(1=1)
	where ssn.port_id = tots.port_id
	and ssn.currently_owned <> 0
	group by ticker, company_name, ssn.shares, tot_shares, tot_price
) alls;


-- Buys
select ticker as "Ticker", 
	company_name as "Company", 
	sps.action_date "Buy Date", 
	price_per_share "Price", 
	round(case when multiplier is null or multiplier > 1 then num_shares else -1*num_shares end ,4) "# shares", 
	round((price_per_share*num_shares),2) "Cost - Commissions", 
	round(initial_Fee,2) "Purchase Fee", 
	round(total_add_fees,2) "Misc Fees",
	round((price_per_share*num_shares) + initial_Fee + total_add_fees,2) "Grand Total"
from stock_desc sd
inner join stock_purc_sold sps
	using(stock_id)
left join stock_split ss
	using(split_his)
where sps.act_id in( 1, 10 )
and currently_owned in(0,1)
order by sps.action_date;



select ticker as "Ticker", 
	company_name as "Company", 
	action_date "Sell Date", 
	price_per_share "Price", 
	round(num_shares,4) "# shares", 
	round((price_per_share*num_shares),2) "Cost - Commissions", 
	round(coalesce(initial_Fee,0),2) "Purchase Fee", 
	round(coalesce(total_add_fees,0),2) "Misc Fees",
	round((price_per_share*num_shares) - (coalesce(initial_Fee,0) + coalesce(total_add_fees,0)),2) "Grand Total"
	-- ,purc_sold_id, trans_id
from stock_desc sd
inner join stock_purc_sold
	using(stock_id)
where act_id = 2
and currently_owned in(0,1)
order by action_date;
 

 
 
 select bought.ticker "Ticker",
	bought.company_name "Company",
	bought.cost_no_fee,
	Bought.initial_Fee +bought.total_add_fees "Bought fees",
	bought.cost_w_fee,
	sold.cost_no_fee "sold no fee",
	sold.initial_Fee + sold.total_add_fees "Sale fees",
	sold.cost_w_fee "sold w fee",	
	sold.cost_no_fee - bought.cost_no_fee "Gail/Loss No Fee",
	sold.cost_w_fee - bought.cost_w_fee "Gail/Loss With Fee",
	max(sps.action_date) "Sale Date"
from(
	select sd.stock_id, 
		ticker,
		company_name,
		round(sum(case when multiplier is null or multiplier > 1 then num_shares else -1*num_shares end),2) num_shares, 
		round(sum((price_per_share*num_shares)),2) cost_no_fee, 
		round(sum(initial_Fee),2) initial_Fee, 
		round(sum(total_add_fees),2) total_add_fees,
		round(sum((price_per_share*num_shares) + initial_Fee + total_add_fees),2) cost_w_fee
	from stock_desc sd
	inner join stock_purc_sold sps
		using(stock_id)
	left join stock_split ss
		using(split_his)
	where sps.act_id in( 1, 10 )
		and currently_owned in(0)
	group by ticker,	sd.company_name 
)bought
left join (
	select sd.stock_id, 
		ticker,
		sd.company_name, 
		sum(num_shares) num_shares,
		round(sum((price_per_share*num_shares)),2) cost_no_fee, 
		round(sum(coalesce(initial_Fee,0)),2) initial_Fee, 
		round(sum(coalesce(total_add_fees,0)),2) total_add_fees,
		round(sum((price_per_share*num_shares) - (coalesce(initial_Fee,0) + coalesce(total_add_fees,0))),2) cost_w_fee
		-- ,purc_sold_id, trans_id
	from stock_desc sd
	inner join stock_purc_sold
		using(stock_id)
	where act_id = 2
	 and currently_owned in(0)
	group by ticker,	sd.company_name
)sold
using(stock_id) 
Inner join stock_purc_sold sps
on(sold.stock_id = sps.stock_id and act_id = 2)
group by bought.ticker ,
	bought.company_name,
	sold.cost_no_fee - bought.cost_no_fee,
	sold.cost_w_fee - bought.cost_w_fee
order by max(sps.action_date)





select payment_num "Payment #", 
	ticker "Ticker",
	div_amount "Dividend",
	num_stocks "Shares Owned", 
	announcement_date "Announced Date", 
	ex_div_date "Ex-date", 
	pay_date "Payed", 
	round(div_amount*num_stocks,2) "Estimated Payout",
	case when pay_date<=now() then cash else 0 end as "recieved"
	-- , div_record.trans_id
	-- , div_rec_id
from stock_desc sd
inner join div_record
using(Stock_id)
left join tranactions
using(trans_id)
where div_record.port_id = 3
and extract(YEAR from pay_date) >= extract(YEAR from now())
order by ex_div_date,announcement_date, payment_num;


	
	

select dateot "Date of Transaction",
	act_desc "Action",
	divi "Dividend",
	cash "Cash +/-",
	round(rt,2) "Run Total",
	tick "Dividend Ticker",
	parcel_id "Parcel Number",
	notes "Notes"
--	,trans_id
 from(
	select 
		dateot,
		act_desc,
		divi,
		cash,
		(@run_tot := coalesce(@run_tot,0) + case when divi is not null then divi else cash end) rt,
		tick,
		notes,
		act_id
		,ticker
		,ab.trans_id
		,parcel_id
		,date_Max_Tran
	from(
		select date_of_transaction dateot,
			act_desc,
			case when t.act_id = 8 then cash end divi,
			case when t.act_id <> 8 then cash end cash,
			case when t.act_id = 8 then sd.ticker end tick
			,notes
			,t.act_id
			,ticker
			,t.trans_id
			,parcel_id
			,date_Max_Tran
		from tranactions t
		left join possible_actions  pa
		using(act_id)
		left join div_record dr
		on(date_of_transaction = pay_date 
			and t.trans_id = dr.trans_id
			and t.act_id = 8)
		left join stock_desc sd
		on(dr.stock_id = sd.stock_id)
		left join (select 
			distinct action_date, parcel_id, tps.trans_id
			FROM interestbearing_purc_sold tps
			Inner join tax_lien tl
			on (tl.lien_id = tps._id
				and tps.port_id = tl.port_id)
		)Lien
		on(action_date = date_of_transaction
			and lien.trans_id = t.trans_id)
			left join ( select max(date_of_transaction) date_Max_Tran, port_id 
		from tranactions 
		group by port_id
		) mx
	 on(t.port_id=mx.port_id)
		where t.port_id in (3)     -- 1,2,4    total for account
		order by date_of_transaction, trans_id
	)ab

	join (select @run_tot:=0)c
	-- join (select @run_tot:=0, trans_id, date_of_transaction from tranactions order by date_of_transaction)c
	-- on( c.trans_id = ab.trans_id and c.date_of_transaction = ab.dateot)
)d
where extract(YEAR from date_Max_Tran) = extract(YEAR from dateot) 




-- PAAY
select ticker, avgyield
from(
select ticker, avg(calc_yield) avgyield, COUNT(1) wks
from yield_hist 
right join stock_desc
using(stock_id)
where date_adj_close >= adddate(now(),-371)
group by ticker
)b
where wks >=52
ORDER BY ticker;



--Announcement estimation date
Select Expected "Next Expected around", Current "Current Announcement Date", case when trim(timing) <> '' and abs(datediff(Expected, Current)) < 10 then '' else timing end "On Time?",Ticker 
from(
select DATE_ADD(announcement_date, INTERVAL 366 DAY) Expected, mannouncement_date Current, 
case when (DATE_ADD(announcement_date, INTERVAL 366 DAY) <= case when mannouncement_date > now() then mannouncement_date else now() end )																			
	then 'Late' 
	when announcement_date is null then 'N/A' else '' end timing, Ticker
from(
	select max(announcement_date) announcement_date, dr.stock_id 
	from div_record dr
	inner join (
		select max(announcement_date) mannouncement_date, stock_id from div_record group by stock_id
	)mad
	on(mad.stock_id = dr.stock_id)
	where announcement_date  between DATE_ADD(mannouncement_date, INTERVAL -390 DAY) and DATE_ADD(mannouncement_date, INTERVAL -250 DAY)
	AND dr.payment_num <> 0
	group by dr.stock_id
)b
Right join stock_desc sd
on(b.stock_id = sd.stock_id)
inner join (
	select max(announcement_date) mannouncement_date, stock_id from div_record group by stock_id
)mad
on(mad.stock_id = sd.stock_id)
where currently_owned <> 0 and payment_per_year = 4

)a
order by case when trim(timing) <> '' and abs(datediff(Expected, Current)) < 10 then '' else timing end

	

-- Time in market
select datediff(now(),dat)/365.25 , ticker 
from(
select min(action_date) dat, ticker, stock_id
from stock_desc
inner join stock_purc_sold
using(stock_id)
where currently_owned = 1
and port_id <> 1
group by ticker, stock_id
)b
 group by ticker
 order by datediff(now(),dat)/365.25 desc;
 
 

-- Average Divi over a years time. 
select ticker, count(ticker), sum(recieved), round((sum(recieved)/count(ticker))/avg(owned),5) from(
select payment_num "Payment #", 
	ticker "Ticker",
	div_amount "Dividend",
	num_stocks "Owned", 
	announcement_date "Accounced Date", 
	ex_div_date "Ex-date", 
	pay_date "Payed", 
	round(div_amount*num_stocks,2) "Estimated Payout",
	case when pay_date<=now() then cash else 0 end as "recieved"
	-- , div_record.trans_id
	-- , div_rec_id
from stock_desc sd
inner join div_record
using(Stock_id)
left join tranactions
using(trans_id)
where -- div_record.port_id = 6
-- and
 extract(YEAR from pay_date) = extract(YEAR from now())-1
and ticker in ('VIG', 'BND', 'DOL', 'SCHD', 'VFIAX', 'VIMAX', 'PRTIX', 'PRDGX', 'PRTIX','PRDGX','VBTLX', 'VEXAX','VTI', 'VT')
order by ex_div_date,announcement_date, payment_num
)c 
group by ticker;


-- Active options summary
SELECT sd.ticker, so.Call_or_Put, so.strike, SUM(case when so.act_id <24 then 1 ELSE -1 END) contracts, sum(t.cash) "cost-profit"
FROM stock_options_group sog
INNER JOIN stock_options so
USING(GROUP_id)
INNER JOIN tranactions t
USING(trans_id)
INNER JOIN stock_desc sd
ON(sog.stock_id = sd.stock_id)
WHERE sog.current_contracts > 0
GROUP BY sd.ticker, so.Call_or_Put, so.strike


--Options summation
select ticker, 
	sog.action_date "Initail in", 
	so2.out_date,
	case when got_crdt_or_dbt = 1 then 'As Credit' else 'As Debit' end "Came in", 
	max_contracts "Total Contracts",
	strategy_desc "Stratagy in Play",
	sog.notes "Notes", 
	so.experation_date_date "Experation Date", 
	case when so2.act_desc is not null then act_desc else NULL end Expired,
	cash "Protfit/Loss",
	Week(so2.out_date)-minwk.wk
	-- ,case when wash.group_id IS NOT NULL then 'W' ELSE NULL END "Wash Sale?"
from stock_options_group sog
inner join stock_options so
using (group_id)
inner join (
	select max(action_date) out_date, group_id ,act_desc
	from stock_options 
	left join (select act_desc, options_id
				From stock_options so
				inner join possible_actions pa
				on(so.act_id = pa.act_id
					and pa.act_id = 26)
	)expi
	on(stock_options .options_id = expi.options_id)
	where in_out = 0
	group by group_id ,act_desc
) so2
using (group_id)
LEFT JOIN (
	select Week(min(action_date))-1 wk 
	from stock_options 
	where in_out = 0
) minwk
on(1=1)
inner join stock_desc sd
on(sog.stock_id = sd.stock_id)
left JOIN (
	select SUM(t2.cash + coalesce(t3.cash,0)) cash, so2.group_id 
	from stock_options so2 
	left JOIN tranactions t2
	ON(t2.trans_id = so2.trans_id)
	LEFT JOIN stock_purc_sold sps
	USING(purc_sold_id)
	left JOIN tranactions t3
	ON(t3.trans_id = sps.trans_id)
	GROUP BY so2.group_id
)t
on( t.group_id = so.group_id)
Inner join stock_options_strategy
using(strat_id)
/*LEFT JOIN (
	SELECT distinct  a.group_id -- , b.group_id
FROM stock_options a
INNER JOIN stock_options b
USING (stock_id)
WHERE a.group_id <> b.GROUP_id 
	AND ( abs(DATEDIFF( a.action_date, b.action_date)) < 30 
		AND a.strike = b.strike )
) wash
ON(wash.group_id = so.group_id) 
*/
where current_contracts <1
-- AND EXTRACT(YEAR from so2.out_date) = EXTRACT(YEAR from NOW())
 group by ticker, sog.action_date, got_crdt_or_dbt, max_contracts, strategy_desc, sog.notes, so.experation_date_date,	so2.out_date, cash
order by 	sog.action_date;



-- Check Fees for options 
SELECT Ticker, action_date, so.addition_total_fees, cash,so.trans_id, options_id
FROM stock_options so
INNER JOIN stock_desc sd
USING( stock_id)
left JOIN tranactions 
USING(trans_id)
WHERE so.action_date > STR_TO_DATE('4-28-2021','%m-%d-%Y') 
AND SO.act_id IN(24,25)
ORDER BY action_date;


-- washes for options
SELECT distinct wash group_id, strike, 
case when wash = ga then ca ELSE cb END wash_sale,
 EXTRACT(YEAR FROM action_date) year 
FROM(
SELECT a.ga, a.gb, a.strike, ca, cb, 
case when (ca < 0 AND cb > 0) OR (ca > 0 AND cb < 0) 
	then case when ca < 0 then a.ga ELSE a.gb END 
	ELSE 0 END wash
FROM(
	SELECT ga, gb, strike, sum(ca) ca
	FROM(
		SELECT a.group_id ga, b.group_id gb, a.strike,  t.cash ca -- , b.group_id gb, a.strike
		FROM stock_options a
		INNER JOIN stock_options b
		USING (stock_id)
		INNER JOIN tranactions t
		ON(a.trans_id = t.trans_id)
		WHERE a.group_id <> b.GROUP_id 
			AND ( abs(DATEDIFF( a.action_date, b.action_date)) < 30 
				AND a.strike = b.strike )
		group by a.group_id,  b.group_id,  a.strike,  t.cash
	)a
	GROUP BY ga, strike, gb
)a
INNER JOIN(
	SELECT ga, gb, strike, sum(cb) cb
	FROM(
		SELECT a.group_id ga, b.group_id gb, a.strike,  t.cash cb-- , b.group_id gb, a.strike
		FROM stock_options a
		INNER JOIN stock_options b
		USING (stock_id)
		INNER JOIN tranactions t
		ON(b.trans_id = t.trans_id)
		WHERE a.group_id <> b.GROUP_id 
			AND ( abs(DATEDIFF( a.action_date, b.action_date)) < 30 
				AND a.strike = b.strike )
		group by a.group_id,  b.group_id,  a.strike,  t.cash
	)a
	GROUP BY ga, strike, gb
)b
ON(a.ga =b.ga and a.gb = b.gb AND  a.strike=b.strike)
)washers
LEFT JOIN stock_options_group
ON(wash = GROUP_id)
WHERE wash <> 0




-- update div
update div_record set div_amount = 0.27826 where div_rec_id = 112
 -- update dividend increase
  update stock_desc set current_Quarterly_dividend = .06 
where ticker = 'WEN'

-- insert div
insert into div_record(port_id, stock_id, payment_num, announcement_date, ex_div_date, record_date, pay_date, num_stocks, div_amount)
select ssn.port_id, ssn.stock_id, case when pay.mxpmt is not null then pay.mxpmt  else 1 end mxpmt, 
STR_TO_DATE('5-15-2021','%m-%d-%Y') announcement, 
STR_TO_DATE('6-29-2021','%m-%d-%Y') ex_div_date, 
STR_TO_DATE('7-3-2021','%m-%d-%Y') record_date, 
STR_TO_DATE('7-17-2021','%m-%d-%Y') pay_date, 
ssn.shares, sd.current_Quarterly_dividend
from stock_desc sd
inner join stable_share_num ssn
on(sd.stock_id = ssn.stock_id
	AND ssn.currently_owned =1)
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
where sd.ticker = 'AFSI'



-- stock desc
insert into stock_desc (ticker, associated_market, company_name, current_Quarterly_dividend, payment_per_year, currently_owned, website,cred_rat_snp)
select 'EAT', 'NYSE', 'Brinker International, Inc.', 0.32, 4, 1, 'http://phx.corporate-ir.net/phoenix.zhtml?c=119205&p=irol-irhome', 17;

-- purcharse
insert into stock_purc_sold(port_id, stock_id, action_date, num_shares, price_per_share, initial_Fee, total_add_fees, act_id, Notes)
select 3, -- port
 stock_id,  -- stock_id
STR_TO_DATE('10-15-2021','%m-%d-%Y'), -- date
20, -- # of shares
59, -- price
4.95,  -- basic fee
0,  -- addition fees
1,  -- action
NULL -- Notes
from stock_desc 
where ticker = 'WMT';

-- shares
insert into stable_share_num(port_id, stock_id, shares, Create_dttm, last_updated_dttm)
select port_id, sd.stock_id, sum(num_shares), now(),now()
from stock_desc sd
left join stock_purc_sold sps
on(sd.stock_id = sps.stock_id)
where ticker = 'BF-B'
and port_id = 3
group by port_id, sd.stock_id, now(),now();

-- or  adding to stable stocks - no splits
update stable_share_num  a
Join (
		select sum(case when act_id = 1 then num_shares when act_id = 2 then -num_shares end) shares
		, port_id
		,stock_id
	FROM stock_purc_sold
	WHERE STOCK_ID in (select stock_id from stock_desc where ticker in ('PRDGX', 'RPMGX', 'PRSCX', 'PRTIX'))
	-- and port_id =
	group by port_id, stock_id
)b
on (a.stock_id = b.Stock_id 
	and a.port_id = b.port_id)
set a.shares = b.shares;

-- If we have any divi's pending we want to update them.
update DIV_record  a
JOIN (
	SELECT case when ssn.shares <> dr.num_stocks then ssn.shares ELSE dr.num_stocks END num_stocks, ssn.shares, dr.num_stocks old_num_stocks, dr.div_rec_id
	FROM DIV_record dr
	inner join stock_desc sd
	using(stock_id)
	INNER JOIN stable_share_num ssn
	ON(dr.stock_id = ssn.stock_id
		AND dr.port_id = ssn.port_id)
	WHERE dr.trans_id IS NULL
		AND dr.ex_div_date > cast(NOW() AS DATE)
		AND dr.PORT_id = 23
		AND sd.ticker = 'SIG' 
)b
on (a.div_rec_id = b.div_rec_id)
set a.num_stocks = b.num_stocks;


-- trans
insert into tranactions(port_id, act_id, cash, date_of_transaction, temp_id, credit_debit)
select port_id, act_id, -- port and act id
case when act_id = 1 then -1 else 1 end*((price_per_share*num_shares)+(initial_Fee+total_add_fees)), -- total cost
action_date,
purc_sold_id,
0 credit_debit
from stock_purc_sold 
where trans_id is null;




update stock_purc_sold as spc
join tranactions as t on temp_id = purc_sold_id
 set spc.trans_id = t.trans_id
 where t.temp_id is not null;

  update tranactions set temp_id = null where temp_id is not null;
  
  
  
-- Update Price per share  
UPDATE stable_share_num a
LEFT JOIN(
SELECT stock_id, port_id, (price_per_share+coalesce(shares_sold, 0))/num_shares Price_per_share, shares_sold, price_per_share/num_shares, num_shares
	FROM(
		 SELECT SUM(case when spc.act_id IN(2,20) then -1 ELSE 1 END * spc.num_shares) num_shares, 
		 SUM(	case when spc.act_id IN(2,20) then 0 ELSE 1 END * spc.price_per_share*spc.num_shares) price_per_share,
		 SUM(	case when spc.act_id IN(2,20) then 1 ELSE 0 END * shares_sold) shares_sold
		 ,spc.stock_id
		 ,spc.port_id
		 FROM stock_purc_sold spc
		 left JOIN (
			 SELECT stock_id, port_id,  sum(spc2.price_per_share * sl.share_num * -1) shares_sold
			 From stocks_sale_lot sl
			 LEFT JOIN stock_purc_sold spc2
				ON(sl.purc_sold_id_buy = spc2.purc_sold_id)
				GROUP BY  stock_id, port_id
				having sum(spc2.price_per_share * sl.share_num * -1) <> 0
		)salelot
		ON(salelot.stock_id = spc.stock_id AND salelot.port_id = spc.port_id)
	  -- WHERE spc.stock_id = 40 AND spc.port_id = 20
		GROUP BY spc.stock_id, spc.port_id
	)c

)b
ON(a.stock_id =b.stock_id
	AND a.port_id = b.port_id)
SET a.Price_per_share = b.Price_per_share
-- where a.port_id in(2)
  
 
-- update historical div.
insert into historical_div(stock_id, first_payment, quarterly, annual)
select distinct stock_id, pay_date, div_amount, div_amount*payment_per_year
from(
	select stock_id, first_payment from historical_div hd1
	where first_payment in (select max(first_payment) 
	from historical_div where stock_id = hd1.stock_id)
	group by stock_id
)his
right join div_record
using(stock_id)
left join stock_desc
using(stock_id) 
where payment_num = 1
and (first_payment < pay_date or first_payment is null)
AND payment_per_year > 0;


-- insert cash
insert into tranactions(port_id, act_id, cash, date_of_transaction, credit_debit, notes)
values(2, 6, 100, STR_TO_DATE('12-31-2021','%m-%d-%Y'), 1, null);

-- Withdrawl
insert into tranactions(port_id, act_id, cash, date_of_transaction, credit_debit, notes)
values(2, 7, 100, STR_TO_DATE('12-31-2021','%m-%d-%Y'), 0, null);

-- insert interest
insert into tranactions(port_id, act_id, cash, date_of_transaction, credit_debit, notes)
values(3, 9, .04, STR_TO_DATE('04-16-2021','%m-%d-%Y'), 1, null);


-- update purchas/sale
select * FROM stock_purc_sold
WHERE STOCK_ID = (select stock_id from stock_desc where ticker = 'COP');

update stock_purc_sold set price_per_share = 35.9699 where purc_sold_id = 49;

update tranactions set cash = (select (price_per_share*num_shares)+initial_Fee+total_add_fees from stock_purc_sold where purc_sold_id = 49) where trans_id = 285;





-- history of stock_desc
insert into stock_desc_history(old_ticker,old_company_name,old_market,old_cred_rat_snp)
select ticker, company_name, associated_market,cred_rat_snp 
from stock_desc where ticker = 'RZOR'



-- update yield history so long as it's on current div increase.  If div increase goes up..... see below
UPDATE yield_hist a 
    JOIN (
	select annual, yh.stock_id, date_adj_close, (annual/adj_close)*100 per 
	from yield_hist yh
	left join (
		select first_payment, annual, stock_id from(
					select stock_id, first_payment, annual
					from historical_div hd
					where first_payment = (select max(hd2.first_payment) 
													from historical_div hd2 
													where hd2.stock_id = hd.stock_id
												)
					order by stock_id
			)c
	)stk
	on(yh.stock_id = stk.stock_id)
		where  -- first_payment <= date_adj_close
--		and
		calc_yield is null
		and act_id = 12
	
)b
on(a.stock_id = b.stock_id and a.date_adj_close = b.date_adj_close)
SET a.calc_yield = b.per;


/* -- this is the below, goes inside b.
	select annual, yh.stock_id, date_adj_close, (annual/adj_close)*100 per 
	from yield_hist yh
	left join (
		select first_payment, annual, stock_id from(
			select stock_id, first_payment, annual, @row:=@row+1 as row_number from historical_div 
				join (select @row:=0)a
				on(1=1)
				where stock_id = (select stock_id from stock_desc where ticker = 'HAS')
				order by first_payment
			)c
			where row_number =2
	)stk
	on(yh.stock_id = stk.stock_id)
	where yh.stock_id = (select stock_id from stock_desc where ticker = 'HAS')
		and first_payment <= date_adj_close
*/


/*  Sale  */
-- insert deed
insert into stock_purc_sold(port_id, stock_id, action_date, num_shares, price_per_share, initial_Fee, total_add_fees, act_id, Notes)
select 3, -- port
 stock_id,  -- stock_id
STR_TO_DATE('09-07-2021','%m-%d-%Y'), -- date
20, -- # of shares
28.70, -- price
4.95,  -- basic fee
0,  -- addition fees
2,  -- action
NULL -- Notes
from stock_desc 
where ticker = 'CSX';

-- update the number of shares.
update stable_share_num  a
Join (
		select sum(case when act_id = 1 then num_shares when act_id = 2 then -num_shares end) shares
		, port_id
		,stock_id
	FROM stock_purc_sold
	WHERE STOCK_ID in (select stock_id from stock_desc where ticker in ('PRDGX', 'RPMGX', 'PRSCX', 'PRTIX'))
	-- and port_id =
	group by port_id, stock_id
)b
on (a.stock_id = b.Stock_id 
	and a.port_id = b.port_id)
set a.shares = b.shares, currently_owned = case when b.shares = 0 then 0 else currently_owned end,
a.Price_per_share = case when b.shares = 0 then NULL else a.Price_per_share end;

-- insert the transaction
insert into tranactions(port_id, act_id, cash, date_of_transaction, temp_id, credit_debit)
select port_id, act_id, -- port and act id
case when act_id = 1 then -1 else 1 end*((price_per_share*num_shares)+((initial_Fee+total_add_fees)*-1)), -- total cost
action_date,
purc_sold_id,
1
from stock_purc_sold 
where trans_id is null;


Update stock_purc_sold as spc
join tranactions as t on temp_id = purc_sold_id
 set spc.trans_id = t.trans_id
 where t.temp_id is not null;

  update tranactions set temp_id = null where temp_id is not null;
-- end insert transaction

-- update current stock in stock desc

update stock_desc set currently_owned = 0 where ticker = 'CSX';

/* end Sale */




-- Sale lot

SELECT ss.purc_sold_id_buy, sps.num_shares, SUM(ss.share_num), sps.num_shares-SUM(ss.share_num)   
FROM stocks_sale_lot ss
INNER JOIN stock_purc_sold sps
ON(sps.purc_sold_id = ss.purc_sold_id_buy)
INNER JOIN stock_desc sd
ON(sps.stock_id = sd.stock_id)
WHERE sd.ticker = 'VIMAX'
	AND sps.port_id = 5
GROUP BY ss.purc_sold_id_buy;

SELECT *
from stock_purc_sold sps
INNER JOIN stock_desc sd
ON(sps.stock_id = sd.stock_id)
WHERE sp.act_id = 2
	and sd.ticker = 'VIMAX'
	AND sps.port_id = 5
	AND ACTION_date >= STR_TO_DATE('04-15-2021','%m-%d-%Y')


INSERT into stocks_sale_lot(purc_sold_id, purc_sold_id_buy, share_num)
values(913,304, 0.0470);

-- end sale lot





-- options getting in
set @action_dates := STR_TO_DATE('06-12-2021','%m-%d-%Y');
set @settle_dates := STR_TO_DATE('06-15-2021','%m-%d-%Y');
set @expiration_dates := STR_TO_DATE('06-12-2021','%m-%d-%Y');
SET @ticker := 'AMZN';
SET @contracts := 1;
SET @port_id := 7;

 
insert into stock_options_group(stock_id, port_id, action_date, strat_id, got_crdt_or_dbt, current_contracts, max_contracts, collateral, Notes)
select stock_id,
@port_id, 
@action_dates action_date, 
2 strat_id, 
1 got_crdt_or_dbt, 
@contracts current_contracts, 
@contracts max_contracts, 
1000 collateral, 
NULL Notes
from stock_desc
where ticker = @ticker;


insert into stock_options(port_id, stock_id, act_id, in_out, Call_or_Put, option_price, initial_fee, addition_total_fees, strike, contracts, action_date, Settle_date, experation_date_date, group_id)
select @port_id, 
	stock_id,
	22 act_id, 
	1 in_out, 
	'C' Call_or_Put, 
	7.53 option_price, 
	0 initial_fee, 
	0 addition_total_fees, 
	2660 strike, 
	@contracts contracts, 
	@action_dates action_date, 
	@settle_dates Settle_date, 
	@expiration_dates experation_date_date, 
	(select max(group_id) from stock_options_group) group_id
	from stock_desc
	where ticker = @ticker
	 	union all 
	select @port_id, 
	stock_id,
	24 act_id, 
	1 in_out, 
	'C' Call_or_Put, 
	9.53 option_price, 
	0 initial_fee, 
	.04 addition_total_fees, 
	2650 strike, 
	@contracts contracts, 
	@action_dates action_date, 
	@settle_dates Settle_date, 
	@expiration_dates experation_date_date, 
	(select max(group_id) from stock_options_group) group_id 
	from stock_desc
	where ticker = @ticker
	;



-- options getting out
set @action_dates := STR_TO_DATE('11-01-2021','%m-%d-%Y');
set @settle_dates := STR_TO_DATE('11-04-2021','%m-%d-%Y');
SET @ticker := 'SPY';
SET @contracts := 1;
SET @port_id := 7;

  insert into stock_options(port_id, stock_id, act_id, in_out, Call_or_Put, option_price, initial_fee, addition_total_fees, strike, contracts, action_date, Settle_date, experation_date_date, group_id)
select distinct sog.port_id, 
	sd.stock_id,
	act.act_id, 
	0 in_out, 
	Call_or_Put, 
	case when act.act_id = 23 
		then 3.52 
		when act.act_id = 25 
			then 1.82
		ELSE 0
		 end option_price, 
	case when act.act_id = 25 then 0  ELSE 0 end initial_fee, 
	case when act.act_id = 25 then 0.02  ELSE 0 end addition_total_fees, 
	strike, 
	@contracts contracts, 
	@action_dates action_date, 
	@settle_dates Settle_date, 
	experation_date_date, 
	sog.group_id
	from stock_desc sd
	INNER JOIN stock_options_group sog
	ON(sog.stock_id = sd.stock_id)
	INNER JOIN stock_options so
	ON( sog.port_id=so.port_id
		and sog.group_id = so.group_id 
		AND sog.stock_id = so.stock_id)
	LEFT JOIN (SELECT act_id FROM possible_actions WHERE act_id IN (23,25)) act
		ON(case when act.act_id = 25 then 22 END =so.act_id
			or case when act.act_id = 23 then 24 END = so.act_id)
	where ticker = @ticker
		AND sog.PORT_id = @port_id 
		AND so.in_out = 1
		and sog.current_contracts >0
		AND sog.action_date = so.action_date
		-- and sog.action_date = STR_TO_DATE('2-22-2021','%m-%d-%Y')
		;
		
		
	
	Update stock_options_group set current_contracts = 0, overall_gain_loss = 0, ExpiredWorthless = 0, Notes = NULL -- , daytrade = 1
	where stock_id = (select stock_id from stock_desc where ticker =  @ticker)
		and port_id = @port_id 
		and current_contracts > 0;
		

-- options Transactions
 insert into tranactions(port_id, act_id, cash, date_of_transaction, temp_id, credit_debit)
select port_id, act_id, case when act_id in( 22, 23) then -1*(((100*contracts)*option_price)+((initial_fee+addition_total_fees))) else 1*(((100*contracts)*option_price)+((initial_fee+addition_total_fees)*-1)) end 
, action_date, options_id, case when act_id in( 22, 23) then 0 else 1 end 
from stock_options
where action_date <= cast(now() as date)
and stock_options.trans_id is null;


update stock_options as spc
join tranactions as t on temp_id = options_id
 set spc.trans_id = t.trans_id
 where t.temp_id is not null;
 
  update tranactions set temp_id = null where temp_id is not null;

-- Fee fix options
UPDATE tranactions t
JOIN(
	SELECT case when so.act_id in( 22, 23) then -1*(((100*contracts)*option_price)+((initial_fee+addition_total_fees))) else 1*(((100*contracts)*option_price)+((initial_fee+addition_total_fees)*-1)) end  so_Cash, cash, trans_id
	FROM stock_options so
	INNER JOIN tranactions t
	USING(trans_id)
	WHERE cash <> case when so.act_id in( 22, 23) then -1*(((100*contracts)*option_price)+((initial_fee+addition_total_fees))) else 1*(((100*contracts)*option_price)+((initial_fee+addition_total_fees)*-1)) end 
) t2
ON(t.trans_id = t2.trans_id)
SET t.cash = t2.so_Cash;



-- if assigned
UPDATE stock_options so
JOIN(
	SELECT so.options_id, asnd.purc_sold_id FROM stock_options so
	INNER JOIN(
		SELECT act_id, purc_sold_id, stock_id, action_date FROM stock_purc_sold 
		WHERE act_id IN(27,28)
	)asnd
	ON(asnd.stock_id = so.stock_id
		AND asnd.act_id = so.act_id
		AND asnd.action_date = so.action_date)
	WHERE so.stock_id = (SELECT stock_id FROM stock_desc WHERE ticker = 'AMZN')
	AND so.act_id IN(27,28)
	AND so.action_date = STR_TO_DATE('1-31-2021','%m-%d-%Y')
)asnd
ON(so.options_id = asnd.options_id)
SET so.purc_sold_id = asnd.purc_sold_id;



-- INSERT TREASURIES
INSERT INTO treasurysecurity(port_id, security_type_id, CUSIP, Admount, Interest, Purchasedate, Issuedate, maturitylenthinweeks, maturitylenthinyears, min_redemption_date, max_redemption_date, Reinvestment_from, Notes)
SELECT 
22 port_id, 
3 security_type_id, 
'912796WM7' CUSIP, 
997.62 Amount, 
1.556 Interest, 
STR_TO_DATE('11-07-2021','%m-%d-%Y') Purchasedate, 
STR_TO_DATE('11-12-2021','%m-%d-%Y') Issuedate, 
8 maturitylenthinweeks, 
null maturitylenthinyears, 
STR_TO_DATE('1-07-2021','%m-%d-%Y') min_redemption_date, 
STR_TO_DATE('1-07-2021','%m-%d-%Y') max_redemption_date, 
null Reinvestment_from, 
null Notes;


-- insterst for tresuries
  INSERT INTO interestbearing_purc_sold (port_id, _id, action_date, face_value, initial_Fee, total_add_fees, Interest, act_id)
SELECT  i.port_id
, i._id
, t.MAX_redemption_date action_date
, face_value
, 0 initial_Fee
, 0 total_add_fees
,round(face_value + ((i.face_value*(t.interest/100))/case when t.maturitylenthinweeks = 26 then 2 ELSE 4 END)) - face_value  Interest
, 18 act_id
FROM interestbearing_purc_sold i
INNER join treasurysecurity t
ON(i._id = t.scrty_id)
WHERE i.port_id = 22 
AND i._id NOT IN(SELECT _id FROM interestbearing_purc_sold WHERE port_id = 22 and act_id = 18)
AND t.security_type_id = 3  -- Tresure bills
AND t.MAX_redemption_date <= STR_TO_DATE('01-15-2021','%m-%d-%Y')
UNION ALL
SELECT  t.port_id
, t.scrty_id
, t.Purchasedate action_date
, amount
, 0 initial_Fee
, 0 total_add_fees
,0  Interest
, 1 act_id
FROM treasurysecurity t
WHERE t.scrty_id NOT IN(SELECT distinct _id FROM interestbearing_purc_sold WHERE port_id = 22 and act_id IN(1))
AND t.security_type_id = 3  -- Tresure bills
AND t.Purchasedate <= STR_TO_DATE('01-15-2021','%m-%d-%Y')



-- Kickfurther summary
SELECT  brandname, Coop_name, num_packages, price, k.esitmated_interest, k.first_estimated_paydate, i.action_date "In Date",
 MIN(iw.action_date) "First Pay Date", MAX(iw.action_date) "Last Pay Date", -1*t1.cash Cost,  
SUM(t2.cash) Recieved,
ROUND((-1*t1.cash)*(k.esitmated_interest/100), 2) "Total Estimated Interest",
SUM(iw.interest) "Interest Recieved",
case when coalesce(t1.cash,0)+sum(coalesce(t2.cash,0)) < 0 then (-1*(coalesce(t1.cash,0)))+SUM(coalesce(t2.cash,0)) ELSE 0 end  "Left to Break even"
,(SUM(iw.interest)/(-1*t1.cash)) "Percent interest"
, k.deliquent
FROM kicks k
INNER JOIN kicks_brand 
USING (brand_id)
INNER JOIN interestbearing_purc_sold i
ON(k.kick_id = i._id
	AND k.port_id = i.port_id 
	AND i.act_id = 1)
INNER JOIN tranactions t1
ON(i.Trans_id = t1.trans_id)
LEFT JOIN interestbearing_purc_sold iw
ON(k.kick_id = iw._id
	AND k.port_id = iw.port_id 
	AND iw.act_id = 18)
left JOIN tranactions t2
ON(iw.Trans_id = t2.trans_id)
 GROUP BY brandname, Coop_name,num_packages, price, k.esitmated_interest, k.first_estimated_paydate, i.action_date, t1.cash
 
 
 

 
 
 
 


/* End Year insert */
insert into stock_port_endyrvalue (port_id, market_end_date, Actual_cash_in_WFee, Actual_Cash_In_NFee, Value_of, Dividends_total, Total_return_no_div, Total_return_all)
Select 
port_id,
market_end_date, 
Actual_cash_in_WFee,
Actual_Cash_In_NFee,
Value_of,
Dividends_total,
((Value_of-Actual_Cash_In_WFee)/Actual_Cash_In_WFee) Total_return_no_div,
((Dividends_total+(Value_of-Actual_Cash_In_WFee))/Actual_Cash_In_WFee) Total_return_all
from(
	Select 
	6 port_id,
	STR_TO_DATE('12-29-2021','%m-%d-%Y') market_end_date,
	15133.08 Actual_cash_in_WFee,
	15133.08 Actual_Cash_In_NFee,
	16985.19 Value_of,
	80.07 Dividends_total
)a;
  
  
  
  

insert into div_breakdown(div_rec_id, long_trm_gain, shrt_trm_gain, rtn_of_capital, qualifying_div, non_qualifying_div)
select div_rec_id, 
0 long_trm_gain, 
0 shrt_trm_gain, 
0 rtn_of_capital,  
div_amount  qualifying_div,
0 non_qualifying_div
from div_record 
where stock_id in (select stock_id from stock_desc where ticker in( 'EAT', 'GE', 'LOW', 'SYY', 'WM', 'WEN'))
and extract(YEAR from pay_date) = 2014
union all

select div_rec_id, 
0 long_trm_gain, 
0 shrt_trm_gain, 
0, -- div_amount * (6.85/(num_stocks*div_amount)) rtn_of_capital,  
0, -- div_amount * (1.31/(num_stocks*div_amount))  qualifying_div,
div_amount non_qualifying_div
from div_record 
where stock_id in (select stock_id from stock_desc where ticker in( 'DNI'))
and extract(YEAR from pay_date) = 2014



-- Debts



Select * from(
	select dr.debter_id, firstname, lastname, sum(cash) Owed 
	from debter dr
	inner join debts d
	 on(dr.debter_id = d.debter_id)
	inner join tranactions t
	 on(d.trans_id = t.trans_id)
	group by firstname, lastname
)debt
Where Owed <> 0


  
-- debts   
insert into tranactions(port_id, act_id, cash, date_of_transaction, temp_id, credit_debit)
select port_id, act_id, -- port and act id
case when act_id = 19 then -1 else 1 end*(amount), -- total cost
actionDate,
debt_id,
case when act_id = 19 then 0 else 1 end
from debts
inner join debter
using(debter_id)
where trans_id is null;


update debts as spc
join tranactions as t on temp_id = debt_id
 set spc.trans_id = t.trans_id
 where t.temp_id is not null;
 
  update tranactions set temp_id = null where temp_id is not null;
  -- end div gotten


-- Tax Liens OR securities or kickfurther!

insert into tranactions(port_id, act_id, cash, date_of_transaction, temp_id, credit_debit)
select port_id, act_id, -- port and act id
case when act_id = 1 then -1 else 1 end*((face_value+Interest)+((initial_Fee+total_add_fees)*-1)), -- total cost
action_date,
purc_sold_id,
case when act_id = 1 then 0 else 1 end  -- 0 = debit, 1 = credit
from interestbearing_purc_sold 
where trans_id is null;

Update interestbearing_purc_sold as spc
join tranactions as t on temp_id = purc_sold_id
 set spc.trans_id = t.trans_id
 where t.temp_id is not null;

  update tranactions set temp_id = null where temp_id is not null;
  
  
  
-- interest on bonds.  
  SELECT cusip, amount+interest 
FROM(
	SELECT t.scrty_id, CUSIP,  t.amount, SUM(coalesce(i.interest,0)) interest
	FROM treasurysecurity t
	left join interestgained_not_received  i
	ON(t.scrty_id = i.scrty_id)
	WHERE t.security_type_id IN(2,1)
	GROUP BY t.scrty_id, t.amount, t.CUSIP
)a







 insert into stock_purc_sold(port_id, stock_id, action_date, num_shares, price_per_share, initial_Fee, total_add_fees, act_id, Notes)
SELECT port_id,
 case when stock ='CONS BALANCED LIFESTAGE FD' then 39
  when stock ='VANGUARD 500 INDEX FD ADM SHS' then 40
 when stock ='VANGUARD MID-CAP INDEX ADM SHS' then 46
 END stock_id,
 STR_TO_DATE(date_of, '%m/%d/%Y') action_date,
 shares,
 REPLACE(cash_in, '$','')/shares priceper,
 0 initfee,
 0 addfee,
 case when actions = 'PURCHASE OF SHARES' then 1
 ELSE 2 end act_id,
case when abs(ROUND(REPLACE(cash_in, '$','')/shares,2) -REPLACE(priceper,'$','')) >.02 
	then concat(CONCAT('Per Assoicated Bank ', priceper), CONCAT( ', Per Math $',  ROUND(REPLACE(cash_in, '$','')/shares,2)))
ELSE NULL 
END 
FROM hsa401k_temp
WHERE port_id = 5
AND actions IN('PURCHASE OF SHARES', 'Sale OF SHARES');



 insert into tranactions(port_id, act_id, cash, date_of_transaction, credit_debit, notes)
SELECT port_id, 6, REPLACE(cash_in, '$',''), STR_TO_DATE(date_of,'%m/%d/%Y'), 1, NULL
FROM hsa401k_temp
WHERE port_id = 5
AND actions = 'CASH RECEIPT' AND stock ='CASH'
UNION 
SELECT port_id, 11,SUM(-1*REPLACE(cash_in, '$','')), STR_TO_DATE(date_of,'%m/%d/%Y'), 0, NULL
FROM hsa401k_temp
WHERE port_id = 5
AND actions = 'CASH DISBURSEMENT';



 insert into stock_purc_sold(port_id, stock_id, action_date, num_shares, price_per_share, initial_Fee, total_add_fees, act_id, Notes)
SELECT port_id,
 case when stock ='VANGUARD INSTITUTIONAL INDEX' then 105
  when stock ='VANGUARD TTL BND MRKT IDX ADM' then 59
 when stock ='VANGUARD EXTENDED MRKT IND ADM' then 60
 END stock_id,
 STR_TO_DATE(date_of, '%m/%d/%Y') action_date,
 abs(shares),
cash_in/shares priceper,
 0 initfee,
 0 addfee,
 case when actions IN('Investment Purchase', 'Reinvested Dividend', 'Reinvested Interest') then 1
 ELSE 2 end act_id,
 case when abs(ROUND(cash_in/shares,2) -priceper)  
	 then concat(CONCAT('Per Assoicated Bank $', priceper), CONCAT( ', Per Math $',  ROUND(cash_in/shares,2))) 
 ELSE NULL end
FROM hsa401k_temp
WHERE port_id = 20
 AND actions IN('Investment Purchase', 'Reinvested Dividend', 'Reinvested Interest', 'Custodial Management Fee');


 insert into tranactions(port_id, act_id, cash, date_of_transaction, credit_debit, notes)
SELECT port_id, 
case when actions = 'Investment Purchase - Cash receipt'
	then 6
	ELSE 11 end
,cash_in, STR_TO_DATE(date_of,'%m/%d/%Y'), 
case when actions = 'Investment Purchase - Cash receipt'
	then 1
	ELSE 0 end, NULL
FROM hsa401k_temp
WHERE port_id = 20
AND ACTIONs IN( 'Investment Purchase - Cash receipt', 'Custodial Management Fee - Cash disbursement');

 




