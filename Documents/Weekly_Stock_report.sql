Monthly Update September

What I sold/bought.

What Dividends came in.

What stocks on my list announced a dividend.

Any Dividends announce after close on Friday are not recorded here.


Recap of the Month:

Stocks purchases/sales:


Options Gotten into/out of:
Into:


Out of:


Dividends Paid:


Dividends announced:

Overall:
Making Money, watching grass grow.....  (Hey it is good to be in that place!)


set @dates := STR_TO_DATE('02-03-2019','%m-%d-%Y');


-- Stocks purchases/sales:
Select port_name "Portfolio", ticker "Ticker", sum(num_shares) "# Shares", act_desc "Buy/Sell"
from stock_purc_sold
inner join stock_desc
using(stock_id)
inner join portfolio
using(port_id)
inner join possible_actions
using (act_id)
where action_date >@dates
GROUP BY port_name, ticker, act_desc;

-- Options Gotten into/out of:
Select distinct ticker "Ticker", sog.action_date "Date", strategy_desc "Strategy", price_buy_in "Price", experation_date_date "Expiration"
from stock_options_group sog
inner join stock_desc
using(stock_id)
inner join stock_options so
using(group_id)
inner join stock_options_strategy
using(strat_id)
where sog.action_date  > @dates;




Select distinct ticker "Ticker", so.action_date "Date", strategy_desc "Strategy", price_sell_out "Price", case when overall_gain_loss = 1 then 'Profit' else 'Loss' end "Profit/Loss", experation_date_date "Expiration"
from stock_options_group sog 
inner join stock_desc sd
using(stock_id)
inner join stock_options so
using(group_id)
inner join stock_options_strategy sos
using(strat_id)
where so.action_date  > @dates
and in_out = 0;


-- Dividends Paid:
select Distinct ticker "Ticker", record_date "Record Date",pay_date "Pay Date", div_amount "Dividend Amount"
from div_record 
inner join stock_desc
using(stock_id)
where pay_date > @dates
and trans_id is not null;


-- Dividends announced:
select Distinct ticker "Ticker", announcement_date "Announcement", ex_div_date "Ex-Dividend", record_date "Record", pay_date "Pay Date", div_amount "Dividend Amount"
from div_record 
inner join stock_desc
using(stock_id)
where announcement_date >@dates;

