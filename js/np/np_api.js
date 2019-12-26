'use strict';

class NP {

   // public var key = "1e594a002b9860276775916cdc07c9a6";
  constructor(key = '920af0b399119755cbca360907f4fa60', language = 'ru',) {
    this.key = key;
    this.language = language;
  }
  
  // геттер
  //get key() {
  //  return this.key;
 // }

  // сеттер
  //set key(newValue) {
  //  this.key = newValue;
 // }
  
  getCities(page = 0, findByString = '', ref = '') {
		return this.request(
                        'Address',
                        'getCities', 
                        {
			Page : page,
			FindByString : findByString,
			Ref : ref
                        }
                        );
	}
        
    getWarehouses(cityRef = '', page = 0) {
		return this.request(
                        "AddressGeneral",
                        "getWarehouses",
                        {
			CityRef : cityRef,
			Page : page
                        }
                        );
	}
/**
 * 
 * @param {type} model
 * @param {type} method
 * @param {type} properties
 * @returns {undefined}
 */
    request(model, method, properties){
var data = $.ajax({
   "async": false,
  "crossDomain": true,
  "url": "https://api.novaposhta.ua/v2.0/json/",
  "method": "POST",
  "headers": {
    "content-type": "application/json"
  },
  "processData": false,
  "data": "{\r\n\"apiKey\": \"920af0b399119755cbca360907f4fa60\",\r\n \"modelName\": \""+model+"\",\r\n \"calledMethod\": \""+method+"\",\r\n \"methodProperties\": "+JSON.stringify(properties)+"\r\n}",
  success: function (response) {
    return response;
                },
                error: function (e){
                    //console.log(e);
                    return e;
                }
 
});

        if(data){
            return data.responseJSON;
        }
    }
  
  }



