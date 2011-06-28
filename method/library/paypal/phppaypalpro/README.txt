January 30, 2007

Purpose of this Software.

The purpose of this software is to provide you the developer with an easy-to-use interface 
for accessing the Paypal Website Payment Pro Webs service using the Website Payments Pro SOAP 1.1 API.

phpPaypalPro currently supports 4 major operations available for the Website Payments Pro SOAP API namely:

(a) DoDirectPayment
(b) SetExpressCheckout
(c) GetExpressCheckoutDetails
(d) DoExpressCheckoutPayment

Please be on the lookout as more operations are scheduled to be added in the immediate future.

phpPaypalPro's easy-to-use interface allows you to easily select the operation you wish to call, set the parameters
and then execute the call in just a few lines of code. Unlike some other SDK's it allows you add Payment Items easily
when executing the DoDirectPayment or the DoExpressCheckoutPayment operations. 

List of Requirements:

1. PHP Version 5.1 or better. Though it is always better to get the lastest
version of PHP5 because of continuous upgrades to the SOAP extension.
2. On UNIX systems, this extension is only available if PHP was configured with --enable-soap.
OpenSSL also must be compiled into PHP. If you are running PHP on Windows, then the OpenSSL and SOAP extensions must be enabled
by uncommenting the appropriate .dll files in the php.ini configuration file.
3. The Webservice 3-token authentication (Username, Password, Signature) is needed.
4. If making calls on behalf of another person, then the Subject of this person is needed.

When implementing the framework, I would recommend you set max_execution_time runtime option to zero (0).
This sets the maximum time in seconds your script is allowed to run before it is terminated by the parser to unlimited.

This is necessary because sometimes, the Paypal Server may take longer than expected to respond.

This Software is released according to the revised BSD License. 

You should have received a copy of the revised BSD License along with this program.

If not please feel free to contact Israel Ekpo at perfectvista@users.sourceforge.net.
