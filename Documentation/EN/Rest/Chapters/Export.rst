Exports
=======

htmlExcelExport
---------------

The HTML Excel export, exports an html table in a format that is interpretable by excel. Row and cell formats can be set via CSS. There are some special excel CSS keywords for cell fromats:

* Plain Text: mso-number-format:\@
* Format a number to 2 decimal places: mso-number-format:"0\.00"
* Comma separators with 2 decimal places: mso-number-format:\#\,\#\#0\.00
* Date \ Time Formating:
    * American date: mso-number-format:mm\/dd\/yy
    * Month name: mso-number-format:d\\-mmm\\-yyyy
    * Date and Time: mso-number-format:d\/m\/yyyy\ h\:mm\ AM\/PM
    * Short Date: mso-number-format:"Short Date" (05/06/2011)
    * Medium Date: mso-number-format:"Medium Date" (10-jan-2011)
    * Short Time: mso-number-format:"Short Time" (8:67)
    * Medium Time: mso-number-format:"Medium Time" (8:67 AM)
    * Long Time: mso-number-format:"Long Time"  (8:67:25:00)
    * Percentage: mso-number-format:Percent (To two decimal places)
* Scientific Notation: mso-number-format:"0\.E+00"
* Fractions - up to 3 digits: mso-number-format:"\#\ ???\/???"
* Currency (£12.76): mso-number-format:"\0022£\0022\#\,\#\#0\.00"
* 2 decimals, negative numbers in red and signed: mso-number-format:"\#\,\#\#0\.00_ \;\[Red\]\-\#\,\#\#0\.00\ " (1.86-1.66)
* Accounting Format –5,(5): mso-number-format:”\\#\\,\\#\\#0\\.00_\\)\\;\\[Black\\]\\\\(\\#\\,\\#\\#0\\.00\\\\)”
* As per the title - this is simply for "basic" formatt

Example
^^^^^^^

::

    <style type="text/css">

			.headerCell {
				font-weight: bold;
				border-bottom: .5pt solid #000;
				background-color: #FC0;
			}

			td {
				border: .5pt solid #DDD;
				mso-number-format:\@
					}

    </style>