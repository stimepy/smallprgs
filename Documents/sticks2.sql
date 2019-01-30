insert into crypto_purc_sold(port_id, stock_id, tran_stock_id, action_date, num_shares, price_per_share, tran_price_per_to_dol, initial_Fee_in_dol, Fee_in_Stock_id, total_add_fees, act_id, Notes)
select 15, 53, 52, STR_TO_DATE('10-12-2017','%m-%d-%Y') action_date, 1, 58.80, 1 tran_price_per_to_dol, .11, 0.002147, 0, 2, 'Straight $LTC to EOS transaction.  Using dollars for tracking purposes.'



insert into stable_share_num(port_id, stock_id, shares, Create_dttm, last_updated_dttm)
select port_id, sd.stock_id, sum(num_shares), now(),now()
from stock_desc sd
left join crypto_purc_sold sps
on(sd.stock_id = sps.stock_id)
where ticker = '$EOS'
group by port_id, sd.stock_id, now(),now();


insert into tranactions(port_id, act_id, cash, date_of_transaction, temp_id)
select port_id, act_id, -- port and act id
case when act_id = 1 then -1 else 1 end*(((price_per_share*tran_price_per_to_dol)*num_shares)+initial_Fee_in_dol+total_add_fees), -- total cost
action_date,
purc_sold_id -- purc_sold_id
from crypto_purc_sold 
where trans_id is null;




update stock_purc_sold as spc
join tranactions as t on temp_id = purc_sold_id
 set spc.trans_id = t.trans_id
 where t.temp_id is not null;

  update tranactions set temp_id = null where temp_id is not null;
  
  
  
  
  insert into tranactions(port_id, act_id, cash, date_of_transaction, notes)
values(15, 17, 1.96, STR_TO_DATE('12-31-2015','%m-%d-%Y'), 'Zero Sum cash gain/loss since it was a straight exchange.');







  
  insert into tranactions(port_id, act_id, cash, date_of_transaction, notes)
values(15, 17, 1.96, STR_TO_DATE('12-31-2015','%m-%d-%Y'), 'Zero Sum cash gain/loss since it was a straight exchange.');

