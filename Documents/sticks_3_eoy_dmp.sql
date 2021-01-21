-- PORT_ID, DATES, TIMES, TimeZone_GMT, Transaction_Type, stock_id, Asset, Quantity, USD_Spot_Price, USD_Subtotal, USD_Total, USD_Fees, Notes
INSERT INTO dump_coinbase(PORT_ID, DATES, TIMES, TimeZone_GMT, Transaction_Type, stock_id, Asset, Quantity, USD_Spot_Price, USD_Subtotal, USD_Total, USD_Fees, Notes)
	SELECT 14 port_id,
	STR_TO_DATE(substr(DTIMESTAMP, 1, 10), '%Y-%m-%d'),
	SUBSTR(DTIMESTAMP,12,8), 
	0, 
	Transaction_Type,
	stock_id,
	Asset, 
	Quantity, 
	USD_Spot_Price, 
	case when USD_Subtotal <> '' then USD_Subtotal ELSE NULL end, 
	case when USD_Total <> '' then USD_Total ELSE NULL end,
	case when USD_Fees <> '' then USD_Fees ELSE NULL end,
	Notes
	FROM cb_temp
	LEFT JOIN stock_desc
	ON(ticker = CONCAT('$', asset))
ORDER BY STR_TO_DATE(substr(DTIMESTAMP, 1, 10), '%Y-%m-%d')