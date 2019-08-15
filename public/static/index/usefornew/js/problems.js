/*
*.ajax 传送数据到前台
*/
function subto_index()
{
	var InputName=$("input[name ^='radio']:checked");//筛出所有name以radio开头的input元素
	var myarray= new Array();
	var mymap= new Map();//键值对
	var j=0;

	for(i in InputName)
	{
		var x =String(InputName[i].name);
		var y =String(InputName[i].value);
		if(x=="undefined")
			break;
		mymap.set(x,y);
		j++;
	}
	// for(var prop in mymap)
	// {
	// 	console.log("mymap["+prop+"]="+mymap[prop]);
	// }、
	alert("xxx");
	// window.location.href="/public/index/index/checkwork";
	$.ajax({
		type:"post",
		dataType:"json",
		url:"/public/index/index/checkwork",
		data:{}，
		error:function(obj, msg, e){   //异常
                    alert("OH,NO");
                }
	})
}