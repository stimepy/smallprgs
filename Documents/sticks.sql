-- div gotten
insert into tranactions(port_id, act_id, cash, date_of_transaction, temp_id)
select port_id, 8,  round((num_stocks* div_amount),2), pay_date, div_rec_id
from div_record
where pay_date <= cast(now() as date)
and div_record.trans_id is null;


update div_record as spc
join tranactions as t on temp_id = div_rec_id
 set spc.trans_id = t.trans_id
 where t.temp_id is not null;
 
  update tranactions set temp_id = null where temp_id is not null;
  -- end div gotten
  
  
select s.stock_id, ticker, company_name, nm.shares 
from stock_desc s
left join stable_share_num nm
 using(stock_id)
inner join stock_purc_sold sp
 on(sp.stock_id = s.stock_id 
	and sp.port_id = nm.port_id)
where s.currently_owned = 1
	and nm.port_id = 2
	and act_id = 1
group by s.stock_id, ticker, company_name, nm.shares 



select ticker, 
	company_name, 
	shares, 
	round((total/shares),4) pps, 
	total total_purchase,  
	total+(round(lots*4.95,2)) total_purchase_pluscomm,  
	concat(cast(round((shares/tot_shares)*100,4) as char),'%') "% by stock", 
	concat(cast(round((total/tot_price)*100,4) as char),'%') "% by value",  
	annual "Annual Dividend", 
	shares * annual "Annual Tot Div" 
	, concat(cast(round((annual/(total/shares))*100,4) as char),'%')  "Yield on Cost"
-- 	, avg((annual/(total/shares))*100)
from(
	select ticker, company_name, round(ssn.shares) shares, 
		round(sum(case when act_id = 2 then -1*((num_shares*price_per_share))
		else ((num_shares*price_per_share)) end),2) total,
		current_Quarterly_dividend quarterly, 
		current_Quarterly_dividend*4 annual, 
		tot_shares, 
		tot_price,
		sum(case when act_id = 2 and sd.ticker !='DNI' then -1
			when act_id = 1 and spc.initial_Fee <> 0 then 1 
			end) lots
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
			select round(sum(case when act_id = 2 then -1*((num_shares*price_per_share))
								else ((num_shares*price_per_share)) 
						end),2) tot_price, ssn.port_id
			from stable_share_num ssn
			inner join stock_purc_sold spc
			 on(ssn.stock_id = spc.stock_id
				and ssn.port_id = spc.port_id)
			where ssn.port_id = 3
		)a
		on(1=1)
	where ssn.port_id = a.port_id
	group by tot_price
	) tots
	on(1=1)
	where ssn.port_id = tots.port_id
	and currently_owned <> 0
	group by ticker, company_name, ssn.shares, tot_shares, tot_price
) alls;



select ticker as "Ticker", 
	company_name as "Company", 
	action_date "Buy Date", 
	price_per_share "Price", 
	round(num_shares) "# shares", 
	round((price_per_share*num_shares),2) "Cost - Commissions", 
	round(initial_Fee,2) "Purchase Fee", 
	round(total_add_fees,2) "Misc Fees",
	round((price_per_share*num_shares) + initial_Fee + total_add_fees,2) "Grand Total"
from stock_desc sd
inner join stock_purc_sold
	using(stock_id)
where act_id = 1
and currently_owned in(0,1)
order by action_date;




select ticker as "Ticker", 
	company_name as "Company", 
	action_date "Sell Date", 
	price_per_share "Price", 
	round(num_shares,2) "# shares", 
	round((price_per_share*num_shares),2) "Cost - Commissions", 
	round(coalesce(initial_Fee,0),2) "Purchase Fee", 
	round(coalesce(total_add_fees,0),2) "Misc Fees",
	round((price_per_share*num_shares) + coalesce(initial_Fee,0) + coalesce(total_add_fees,0),2) "Grand Total"
from stock_desc sd
inner join stock_purc_sold
	using(stock_id)
where act_id = 2
and currently_owned in(0,1)
order by action_date;
 


select payment_num "Payment #", 
	ticker "Ticker",
	div_amount "Dividend",
	announcement_date "Accounced Date", 
	ex_div_date "Ex-date", 
	num_stocks "Shares Owned", 
	pay_date "Payed", 
	round(div_amount*num_stocks,2) "Estimated Payout",
	case when pay_date<=now() then round(div_amount*num_stocks,2)  else 0 end as "recieved"
	-- , trans_id
	-- , div_rec_id
from stock_desc sd
inner join div_record
using(Stock_id)
where port_id = 3
and extract(YEAR from pay_date) >= extract(YEAR from now())
order by ex_div_date,announcement_date, payment_num;






select dateot "Date of Transaction",
	act_desc "Action",
	divi "Dividend",
	cash "Cash +/-",
	round(rt,2) "Run Total",
	tick "Dividend Ticker",
	notes "Notes"
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
	from(
		select date_of_transaction dateot,
			act_desc,
			case when t.act_id = 8 then cash end divi,
			case when t.act_id <> 8 then cash end cash,
			case when t.act_id = 8 then sd.ticker end tick
			,notes
			,t.act_id
			,ticker
		from tranactions t
		left join possible_actions  pa
		using(act_id)
		left join div_record dr
		on(date_of_transaction = pay_date 
			and t.trans_id = dr.trans_id
			and t.act_id = 8)
		left join stock_desc sd
		on(dr.stock_id = sd.stock_id)
		where t.port_id in (1,2)     -- 1,2,4    total for account
		order by date_of_transaction
	)ab
	join (select @run_tot:=0)c
)d
where extract(YEAR from dateot) >= extract(YEAR from now());


-- PAAY
select ticker, avg(calc_yield) 
from yield_hist 
right join stock_desc
using(stock_id)
where date_adj_close >= adddate(now(),-371)
group by ticker



-- update div
update div_record set div_amount = 0.27826 where div_rec_id = 112

-- insert div
insert into div_record(port_id, stock_id, payment_num, announcement_date, ex_div_date, record_date, pay_date, num_stocks, div_amount)
select ssn.port_id, ssn.stock_id, pay.mxpmt, 
STR_TO_DATE('2-19-2016','%m-%d-%Y') announcement, 
STR_TO_DATE('3-30-2016','%m-%d-%Y') ex_div_date, 
STR_TO_DATE('4-1-2016','%m-%d-%Y') record_date, 
STR_TO_DATE('4-22-2016','%m-%d-%Y') pay_date, 
ssn.shares, sd.current_Quarterly_dividend
from stock_desc sd
inner join stable_share_num ssn
on(sd.stock_id = ssn.stock_id)
left join (
	select max(payment_num)+1 mxpmt, stock_id, port_id
	from div_record d
	where stock_id = (select stock_id 
							from stock_desc sd2
							where sd2.ticker = 'SYY'
							)
	and port_id = 2
	and pay_date = (select max(pay_date) from div_record sd3
						where sd3.stock_id = d.stock_id
						)
	group by stock_id, port_id
)pay
on(ssn.stock_id = pay.stock_id
and ssn.port_id = pay.port_id)
where sd.ticker = 'SYY'
and ((pay.port_id is not null and ssn.port_id = pay.port_id)
 or ssn.port_id = 2
 );



-- stock desc
insert into stock_desc (ticker, associated_market, company_name, current_Quarterly_dividend, currently_owned, website,cred_rat_snp)
select 'EAT', 'NYSE', 'Brinker International, Inc.', 0.32, 1, 'http://phx.corporate-ir.net/phoenix.zhtml?c=119205&p=irol-irhome', 10;

-- purcharse
insert into stock_purc_sold(port_id, stock_id, action_date, num_shares, price_per_share, initial_Fee, total_add_fees, act_id)
select 3, -- port
 stock_id,  -- stock_id
STR_TO_DATE('10-15-2015','%m-%d-%Y'), -- date
20, -- # of shares
59, -- price
4.95,  -- basic fee
0,  -- addition fees
1  -- action
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
update stable_share_num 
set shares =(select sum(case when act_id = 1 then num_shares when act_id = 2 then -num_shares end) FROM stock_purc_sold
WHERE STOCK_ID = (select stock_id from stock_desc where ticker = 'COP'))
where stock_id = (select stock_id from stock_desc where ticker = 'COP')
and port_id = 3;

--trans
insert into tranactions(port_id, act_id, cash, date_of_transaction, temp_id)
select port_id, act_id, -- port and act id
case when act_id = 1 then -1 else 1 end*((price_per_share*num_shares)+initial_Fee+total_add_fees), -- total cost
action_date,
purc_sold_id -- purc_sold_id
from stock_purc_sold 
where action_date = STR_TO_DATE('2-4-2016','%m-%d-%Y');



update stock_purc_sold as spc
join tranactions as t on temp_id = purc_sold_id
 set spc.trans_id = t.trans_id
 where t.temp_id is not null;

  update tranactions set temp_id = null where temp_id is not null;
  
  -- update dividend increase
  update stock_desc set current_Quarterly_dividend = .06 
where ticker = 'WEN'

-- insert interest
insert into tranactions(port_id, act_id, cash, date_of_transaction, notes)
values(3, 9, .04, STR_TO_DATE('04-16-2016','%m-%d-%Y'), null);


-- update historical div.
insert into historical_div(stock_id, first_payment, quarterly, annual)
select stock_id, pay_date, div_amount, div_amount*4
from(
	select stock_id, first_payment from historical_div hd1
	where first_payment in (select max(first_payment) 
	from historical_div where stock_id = hd1.stock_id)
	group by stock_id
)his
right join div_record
using(stock_id)
where payment_num = 1
and (first_payment < pay_date or first_payment is null);


-- insert cash
insert into tranactions(port_id, act_id, cash, date_of_transaction, notes)
values(2, 6, 100, STR_TO_DATE('12-31-2015','%m-%d-%Y'), null);

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
		where  first_payment <= date_adj_close
		and calc_yield is null
	
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
insert into stock_purc_sold(port_id, stock_id, action_date, num_shares, price_per_share, initial_Fee, total_add_fees, act_id)
select 3, -- port
 stock_id,  -- stock_id
STR_TO_DATE('09-07-2016','%m-%d-%Y'), -- date
20, -- # of shares
28.70, -- price
4.95,  -- basic fee
0,  -- addition fees
2  -- action
from stock_desc 
where ticker = 'CSX';

-- update the number of shares.
UPDATE stable_share_num a 
    JOIN (
			select sps.port_id, sd.stock_id, (-1*shares)+num_shares shares, stable_id
			from stock_desc sd
			left join stable_share_num ssn
			on (sd.stock_id = ssn.stock_id)
			left join stock_purc_sold sps
			on(sd.stock_id = sps.stock_id)
			where ticker = 'CSX'
			and sps.port_id = 3
			and sps.port_id = ssn.port_id
			and action_date = (select max(action_date) from stock_purc_sold sps2 where sps2.stock_id = sps.stock_id and sps2.port_id = sps.port_id)
			group by port_id, sd.stock_id
)b
on(a.stable_id = b.stable_id)
SET a.shares = b.shares, last_updated_dttm = now();

-- insert the transaction
insert into tranactions(port_id, act_id, cash, date_of_transaction, temp_id)
select port_id, act_id, -- port and act id
case when act_id = 1 then -1 else 1 end*((price_per_share*num_shares)+((initial_Fee+total_add_fees)*-1)), -- total cost
action_date,
purc_sold_id -- purc_sold_id
from stock_purc_sold 
where action_date = STR_TO_DATE('9-7-2016','%m-%d-%Y');


Update stock_purc_sold as spc
join tranactions as t on temp_id = purc_sold_id
 set spc.trans_id = t.trans_id
 where t.temp_id is not null;

  update tranactions set temp_id = null where temp_id is not null;
-- end insert transaction

-- update current stock in stock desc

update stock_desc set currently_owned = 0 where ticker = 'CSX';

/* end Sale */
  
  