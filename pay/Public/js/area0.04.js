

function addressInit(proId,cityId){
	if(proId==undefined||proId=="")
		proId=false;
	if(cityId==undefined||cityId=="")
		cityId=false;

	$(".address").html("");
	$(".address").append("<select id='province' name='province'></select>");
	$(".address").append("<select id='city' name='city'></select>");
	$(".address").find("#province").append("<option value=''>选择省份</option>");
	$(".address").find("#city").append("<option value=''>选择市区</option>");
	for(var i=0;i<address.province.length;i++) {

		if(proId&&proId==address.province[i].ProID){
			$(".address").find("#province").append("<option value='" + address.province[i].name + "' title='"+address.province[i].ProID+"' selected>" + address.province[i].name + "</option>");
		}else {
			$(".address").find("#province").append("<option value='" + address.province[i].name + "' title='"+address.province[i].ProID+"'>" + address.province[i].name + "</option>");
		}
	}
	if(proId) {
		for (var i = 0; i < address.city.length; i++) {
			if(address.city[i].ProID==proId) {
				if(cityId&&cityId==address.city[i].CityID) {
					$(".address").find("#city").append("<option value='" + address.city[i].name + "' title='"+address.city[i].CityID+"'  selected>" + address.city[i].name + "</option>");
				}else{
					$(".address").find("#city").append("<option value='" + address.city[i].name + "' title='"+address.city[i].CityID+"'>" + address.city[i].name + "</option>");
				}
			}
		}
	}
}
$(function () {
	addressInit(proId,cityId);
	$(".address").on("change","#province",function(){
		addressInit($(this).find("option:selected").attr("title"));
	});
})