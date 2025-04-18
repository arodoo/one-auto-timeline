-- Add agency_office column to membres_insurance table
-- This is part of INSUR-FIX-003 to separate agency name from agency office/bureau/broker
ALTER TABLE `membres_insurance` 
ADD COLUMN `agency_office` varchar(100) DEFAULT NULL AFTER `agency_name`;
