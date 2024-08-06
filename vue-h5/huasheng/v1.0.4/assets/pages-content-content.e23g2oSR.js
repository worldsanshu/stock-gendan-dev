import{a0 as t,a1 as e,a2 as a,bz as s,ao as i,bQ as n,x as r,y as l,z as o,A as d,B as u,a9 as c,G as p,H as f,a8 as m,I as h,J as _,C as A,O as x,b1 as g,Z as y,aq as b,q as v,K as C,D,E as k,F as B,N as w,co as z}from"./index-DxUBkcQO.js";import{_ as I}from"./uni-nav-bar.D3FG-aeM.js";import{r as N}from"./uni-app.es.WyEFFhdr.js";import{e as S,_ as E}from"./index.D1LAOT4E.js";import{_ as M}from"./u-icon.BCEdui8G.js";import{_ as L}from"./u-text.BBexnxe8.js";import{_ as X}from"./_plugin-vue_export-helper.BCo6x5W8.js";import{z as G,A as R,B as F}from"./index.B2Jj0BgA.js";import"./uni-icons.BDom0B1X.js";import"./uni-status-bar.DTIfAn29.js";import"./u-link.WvmVOVcL.js";const P=X({name:"u-steps-item",mixins:[e,a,{props:{title:{type:[String,Number],default:()=>t.stepsItem.title},desc:{type:[String,Number],default:()=>t.stepsItem.desc},iconSize:{type:[String,Number],default:()=>t.stepsItem.iconSize},error:{type:Boolean,default:()=>t.stepsItem.error}}}],data:()=>({index:0,childLength:0,showLine:!1,size:{height:0,width:0},parentData:{direction:"row",current:0,activeColor:"",inactiveColor:"",activeIcon:"",inactiveIcon:"",dot:!1}}),watch:{parentData(t,e){}},created(){this.init()},computed:{lineStyle(){var t,e;const a={};return"row"===this.parentData.direction?(a.width=this.size.width+"px",a.left=this.size.width/2+"px"):a.height=this.size.height+"px",a.backgroundColor=(null==(e=null==(t=this.parent.children)?void 0:t[this.index+1])?void 0:e.error)?s.error:this.index<this.parentData.current?this.parentData.activeColor:this.parentData.inactiveColor,a},statusClass(){const{index:t,error:e}=this,{current:a}=this.parentData;return a==t?!0===e?"error":"process":e?"error":a>t?"finish":"wait"},statusColor(){let t="";switch(this.statusClass){case"finish":t=this.parentData.activeColor;break;case"error":t=s.error;break;case"process":t=this.parentData.dot?this.parentData.activeColor:"transparent";break;default:t=this.parentData.inactiveColor}return t},contentStyle(){const t={};return"column"===this.parentData.direction?(t.marginLeft=this.parentData.dot?"2px":"6px",t.marginTop=this.parentData.dot?"0px":"6px"):(t.marginTop=this.parentData.dot?"2px":"6px",t.marginLeft=this.parentData.dot?"2px":"6px"),t}},mounted(){this.parent&&this.parent.updateFromChild(),i().then((()=>{this.getStepsItemRect()}))},methods:{init(){if(this.updateParentData(),!this.parent)return n();this.index=this.parent.children.indexOf(this),this.childLength=this.parent.children.length},updateParentData(){this.getParentData("u-steps")},updateFromParent(){this.init()},getStepsItemRect(){this.$uGetRect(".u-steps-item").then((t=>{this.size=t}))}}},[["render",function(t,e,a,s,i,n){const g=A,y=N(r("u-icon"),M),b=x,v=N(r("up-text"),L);return l(),o(g,{class:u(["u-steps-item",[`u-steps-item--${i.parentData.direction}`]]),ref:"u-steps-item"},{default:d((()=>[i.index+1<i.childLength?(l(),o(g,{key:0,class:u(["u-steps-item__line",[`u-steps-item__line--${i.parentData.direction}`]]),style:c([n.lineStyle])},null,8,["class","style"])):p("",!0),f(g,{class:u(["u-steps-item__wrapper",[`u-steps-item__wrapper--${i.parentData.direction}`,i.parentData.dot&&`u-steps-item__wrapper--${i.parentData.direction}--dot`]])},{default:d((()=>[m(t.$slots,"icon",{},(()=>[i.parentData.dot?(l(),o(g,{key:0,class:"u-steps-item__wrapper__dot",style:c({backgroundColor:n.statusColor})},null,8,["style"])):i.parentData.activeIcon||i.parentData.inactiveIcon?(l(),o(g,{key:1,class:"u-steps-item__wrapper__icon"},{default:d((()=>[f(y,{name:i.index<=i.parentData.current?i.parentData.activeIcon:i.parentData.inactiveIcon,size:t.iconSize,color:i.index<=i.parentData.current?i.parentData.activeColor:i.parentData.inactiveColor},null,8,["name","size","color"])])),_:1})):(l(),o(g,{key:2,style:c({backgroundColor:"process"===n.statusClass?i.parentData.activeColor:"transparent",borderColor:n.statusColor}),class:"u-steps-item__wrapper__circle"},{default:d((()=>["process"===n.statusClass||"wait"===n.statusClass?(l(),o(b,{key:0,class:"u-steps-item__wrapper__circle__text",style:c({color:i.index==i.parentData.current?"#ffffff":i.parentData.inactiveColor})},{default:d((()=>[h(_(i.index+1),1)])),_:1},8,["style"])):(l(),o(y,{key:1,color:"error"===n.statusClass?"error":i.parentData.activeColor,size:"12",name:"error"===n.statusClass?"close":"checkmark"},null,8,["color","name"]))])),_:1},8,["style"]))]),!0)])),_:3},8,["class"]),f(g,{class:u(["u-steps-item__content",[`u-steps-item__content--${i.parentData.direction}`]]),style:c([n.contentStyle])},{default:d((()=>[f(v,{text:t.title,type:i.parentData.current==i.index?"main":"content",lineHeight:"20px",size:i.parentData.current==i.index?14:13},null,8,["text","type","size"]),m(t.$slots,"desc",{},(()=>[f(v,{text:t.desc,type:"tips",size:"12"},null,8,["text"])]),!0)])),_:3},8,["class","style"])])),_:3},8,["class"])}],["__scopeId","data-v-10464742"]]);const O=X({name:"u-steps",mixins:[e,a,{props:{direction:{type:String,default:()=>t.steps.direction},current:{type:[String,Number],default:()=>t.steps.current},activeColor:{type:String,default:()=>t.steps.activeColor},inactiveColor:{type:String,default:()=>t.steps.inactiveColor},activeIcon:{type:String,default:()=>t.steps.activeIcon},inactiveIcon:{type:String,default:()=>t.steps.inactiveIcon},dot:{type:Boolean,default:()=>t.steps.dot}}}],data:()=>({}),watch:{children(){this.updateChildData()},parentData(){this.updateChildData()}},computed:{parentData(){return[this.current,this.direction,this.activeColor,this.inactiveColor,this.activeIcon,this.inactiveIcon,this.dot]}},methods:{updateChildData(){this.children.map((t=>{g.func((t||{}).updateFromParent())&&t.updateFromParent()}))},updateFromChild(){this.updateChildData()}},created(){this.children=[]}},[["render",function(t,e,a,s,i,n){const r=A;return l(),o(r,{class:u(["u-steps",[`u-steps--${t.direction}`]])},{default:d((()=>[m(t.$slots,"default",{},void 0,!0)])),_:3},8,["class"])}],["__scopeId","data-v-73785e8d"]]);const Q=X({data:()=>({trading:"",form:"",id:"",profit_loss:"",line_data:[],naviBarTitle:""}),mounted(){},onLoad(t){this.naviBarTitle=t.title,this.id=t.id,G({id:t.id}).then((t=>{this.form=t.data.form})),R({fund_id:t.id}).then((t=>{this.line_data=t.data.line_data,this.profit_loss=t.data.profit_loss;for(var e={xAxis:{type:"category",data:[]},yAxis:{type:"value",axisLabel:{formatter:"{value}%"}},series:[{data:[],type:"line"}]},a=0;a<t.data.line_data.length;a++)e.xAxis.data.push(t.data.line_data[a].value[0]),e.series[0].data.push(parseInt(t.data.line_data[a].value[1]));this.$refs.chart.init(S,(t=>{t.setOption(e)}))}))},methods:{back:y,skip(){b("/pages/buying/buying",{name:this.form.name,id:this.form.id,money:this.form.min_invest_money})},Collect(){F({fund_id:this.form.id}).then((t=>{v({title:t.message,icon:"none",duration:2e3})}))}}},[["render",function(t,e,a,s,i,n){const u=N(r("uni-nav-bar"),I),c=A,p=w,m=N(r("l-echart"),E),x=z,g=N(r("up-steps-item"),P),y=N(r("up-steps"),O);return l(),o(c,null,{default:d((()=>[f(u,{title:t.$t("page.title.fundAssetDetails"),"status-bar":"","background-color":"#E5574F",color:"#fff","left-icon":"left",fixed:"",border:!1,onClickLeft:n.back},null,8,["title","onClickLeft"]),f(c,{class:"page_top_bg"}),f(c,{style:{height:"20rpx"}}),f(c,{class:"fund_title",style:{"z-index":"100"}},{default:d((()=>[f(c,{class:"fund_title_content",style:{"z-index":"100"}},{default:d((()=>[f(p,{src:"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEIAAAA6CAYAAAAEJY9EAAAAAXNSR0IArs4c6QAABLdJREFUaEPtmsvvXVMUxz9fBAPMiGckhDKQGJCIUPwLiPcj8WjVgKKNRz3baquqGIhHPBqNZwf+Aik6MCGm9RxI2ighwYAJSxb7yrnnd/Y5d53f+Z17b3PO7Je7915rf35rr9feYvj+JaCBw38EBhDJEgYQA4hxpxC2CDM7A7geOAw4ETgLOBU4psLfhNdv8FlW+N3XHq3/C/A18C3wE7BD0lcR/xdW1MweBx6LCJnC2A2SHo3IbQPiCSAkJKJQR2M3SVoXWasNiA3AwxEhUxi7TdLaiNw2IJ4EHooImcLY5yTdE5HbBsQm4MGIkCmMfVbSvRG5bUBsBh7ICNkPvNFTonYpcGFGj+2S7psmiA8kXR5RoO1YM3saWDOrIFyvQyQV432lroV8ZJQP+Jx9kl6ZBI6Z7Ut5TNXwqVtEBEQuDDeCNDOH93cNsLkCURWGP5d0XpNFHBQgzOxM4Ebgloxp7wK+BHbm0uS5B2Fmy4DdwAlN/3XAI9BlVTDmGoSZrQBengBAccjHwIoyjHkHUefl6/islzRW5M0tiHQk9gatYTR8o6RHinNnBURTir0g/JnZYirWNiB6Kbrqqs/9kk4q/+fNbCMQKosLayw4Gv6bmb0LXJ2xsi2SQvVQY62RzLAozz3/h5nQtyrjEB1eEwgPmxeVIopv1v2Dd5/K39kpAh1X+uGARxtg7Cg2ZbuVIFKs93bcbTVpbOTIeyj0tl7d9wPwFnBKyhp3e7rdUFNEdFhZl77nQKwHxhxURGJHY7cB9wN/dbSeJ2g35dbKgWhyiB3p1rjMdiDUV6hZca8kP06VX58g3gMunuCINNJpOeCApOOjIJaiL+mRY2dycE3+ouVea6d9Jun8KAgvjG4AbgeyFIPa3gG8Brh5XgN49LkiuMZihr8p6eYoiHOAW5MHX6qOkx8VjyahJusiSDwlKddirL4ENrNXE4iyXI8mftN1bUahPYAXSiPf410n9wuXZMZ7R/z7dFPmZXnO+rYCrye5o7W9Z7k8s+4ngOtS1MOjhpf3IWf5BXBuxYyVwMk1oXWXpKuK88ysrv3vyc8OTxQBD20537FVkofS/z8z25LCa9XGuskszezXzF2mO1G/86xLX8dqjQYQk1r6Zkljdym99CzN7E/giAot3fM7iOxZKzdvzayL5GzsLrO36tPMfgeOyoA4NGgRHoG8K7WYkLms2JyZSxAO08zct7w06TkojVtQUs8tiATjmRbpcmVbfq5BJBiRdl32buJgAOE5iGesh9eES0+0PpKUPUpzD6KUB1T5jXckXdfkS/oE8RtwdBdRo25TZnYncFrKAP8AvB5ofPvUJ4gfgWMrNvF+erCVS6gqe5ZN/+E2v/eVUH0DnJ5R8Hng7txvkla32Vh0jpl5/ZF7HtTNJbCZfQpcEFUuVZNvt5jXZoo/ElnahyJm5h3lK9toNyNzunk6ZGZu/nfNyKbaqNHNY7LkzV9oo8GMzOnupsvMvLX24oxsLKpGdw9OJ4jVUeX6HN/tE+T0psFvnvzBuZfR3p3yKzb/+0jvPaRkqNgS63PDLte7Wz8Dnoh919uj9D532aesxkvgPpWZpqwBRKI/gBhAjB/EwSIGixi3iH8AVh4JWcXv0/oAAAAASUVORK5CYII=",mode:""}),f(c,{class:"tomun"},{default:d((()=>[h(_(i.form.cate_text),1)])),_:1})])),_:1}),f(c,{class:"fund_title_tag",style:{"z-index":"100"}},{default:d((()=>[f(c,{class:"wudumu"},{default:d((()=>[h(_(i.form.how),1)])),_:1})])),_:1}),f(c,{class:"fund_title_tag",style:{"z-index":"100"}},{default:d((()=>[f(c,{class:"wudumu"},{default:d((()=>[h(_(i.form.num),1)])),_:1})])),_:1})])),_:1}),f(c,{class:"fund_top_info"},{default:d((()=>[f(c,{class:"fund_top_info_item",style:{"z-index":"100"}},{default:d((()=>[f(c,{class:"mun1"},{default:d((()=>[h(_(t.$t("fundAssetDetails.text.numberOfUsers")),1)])),_:1}),f(c,{class:"mun2"},{default:d((()=>[h(_(i.form.use_num),1)])),_:1})])),_:1}),f(c,{style:{width:"1rpx",height:"70rpx","background-color":"#fff","z-index":"100"}}),f(c,{class:"fund_top_info_item",style:{"z-index":"100"}},{default:d((()=>[f(c,{class:"mun1"},{default:d((()=>[h(_(t.$t("fundAssetDetails.text.followTheFunds"))+"(CNY)",1)])),_:1}),f(c,{class:"mun2"},{default:d((()=>[h(_(i.form.follow_funds),1)])),_:1})])),_:1})])),_:1}),f(c,{class:"earnings_performance"},{default:d((()=>[f(c,{style:{width:"90%",margin:"auto","z-index":"100000"}},{default:d((()=>[f(c,{style:{height:"20rpx"}}),f(c,{class:"earning_title",style:{"z-index":"100000"}},{default:d((()=>[f(c,{class:"fkuo"},{default:d((()=>[h(_(t.$t("fundAssetDetails.text.revenuePerformance")),1)])),_:1})])),_:1}),f(c,{class:"chart_desc",style:{"z-index":"100000"}},{default:d((()=>[f(c,{class:"chart_desc_item",style:{"z-index":"100000"}},{default:d((()=>[f(c,{class:"chart_desc_line"}),f(c,{class:"thud"},{default:d((()=>[h(_(t.$t("fundAssetDetails.text.rateOfReturn")),1)])),_:1}),f(c,{class:"tomund"},{default:d((()=>[h(_(i.profit_loss)+"%",1)])),_:1})])),_:1}),f(c,{class:"chart_desc_item",style:{"z-index":"100000"}},{default:d((()=>[f(c,{class:"chart_desc_line",style:{"background-color":"#4571ED"}}),f(c,{class:"thud"},{default:d((()=>[h(_(t.$t("fundAssetDetails.text.redemptionNumber")),1)])),_:1}),f(c,{class:"tomund",style:{color:"#333333"}},{default:d((()=>[h(_(i.form.max_retreat),1)])),_:1})])),_:1})])),_:1}),f(c,{style:{height:"450rpx"}},{default:d((()=>[f(m,{ref:"chart"},null,512)])),_:1}),f(c,{class:"trend_data"},{default:d((()=>[f(c,{class:"trend_data_item"},{default:d((()=>[f(c,{class:"tren_esmu"},{default:d((()=>[h(_(t.$t("fundAssetDetails.text.last3Days")),1)])),_:1}),f(c,{class:"renr_rsmu"},{default:d((()=>[h(_(Number(i.form.last_three_days).toFixed(2))+"%",1)])),_:1})])),_:1}),f(c,{class:"trend_data_item"},{default:d((()=>[f(c,{class:"tren_esmu"},{default:d((()=>[h(_(t.$t("fundAssetDetails.text.last7Days")),1)])),_:1}),f(c,{class:"renr_rsmu"},{default:d((()=>[h(_(Number(i.form.last_seven_days).toFixed(2))+"%",1)])),_:1})])),_:1}),f(c,{class:"trend_data_item"},{default:d((()=>[f(c,{class:"tren_esmu"},{default:d((()=>[h(_(t.$t("fundAssetDetails.text.nearly1Month")),1)])),_:1}),f(c,{class:"renr_rsmu"},{default:d((()=>[h(_(Number(i.form.last_month_days).toFixed(2))+"%",1)])),_:1})])),_:1})])),_:1})])),_:1})])),_:1}),f(c,{style:{width:"100%",height:"20rpx","background-color":"#F5F5F5"}}),f(c,{class:"fund_profile"},{default:d((()=>[f(c,{class:"box1"},{default:d((()=>[f(c,{class:"muntods"},{default:d((()=>[h(_(t.$t("fundAssetDetails.text.summaryOfCopyTrading")),1)])),_:1})])),_:1}),f(c,{class:"box2"},{default:d((()=>[f(c,{class:"bumpo"},{default:d((()=>[h(_(t.$t("fundAssetDetails.text.copyName")),1)])),_:1}),f(c,{class:"muntohtd"},{default:d((()=>[h(_(i.form.name),1)])),_:1})])),_:1}),f(c,{class:"box2"},{default:d((()=>[f(c,{class:"bumpo"},{default:d((()=>[h(_(t.$t("fundAssetDetails.text.dateOfEstablishment")),1)])),_:1}),f(c,{class:"muntohtd"},{default:d((()=>[h(_(i.form.establish_date),1)])),_:1})])),_:1}),f(c,{class:"box2"},{default:d((()=>[f(c,{class:"bumpo"},{default:d((()=>[h(_(t.$t("fundAssetDetails.text.investmentType")),1)])),_:1}),f(c,{class:"muntohtd"},{default:d((()=>[h(_(i.form.type_text),1)])),_:1})])),_:1}),f(c,{class:"box2"},{default:d((()=>[f(c,{class:"bumpo"},{default:d((()=>[h(_(t.$t("fundAssetDetails.text.copySize")),1)])),_:1}),f(c,{class:"muntohtd"},{default:d((()=>[h(_(i.form.scale),1)])),_:1})])),_:1}),f(c,{class:"box2"},{default:d((()=>[f(c,{class:"bumpo"},{default:d((()=>[h(_(t.$t("fundAssetDetails.text.copyOrderShare")),1)])),_:1}),f(c,{class:"muntohtd"},{default:d((()=>[h(_(i.form.share),1)])),_:1})])),_:1}),f(c,{class:"box2"},{default:d((()=>[f(c,{class:"bumpo"},{default:d((()=>[h(_(t.$t("fundAssetDetails.text.merchandisingManager")),1)])),_:1}),f(c,{class:"muntohtd"},{default:d((()=>[h(_(i.form.manage),1)])),_:1})])),_:1}),f(c,{class:"box2"},{default:d((()=>[f(c,{class:"bumpo"},{default:d((()=>[h(_(t.$t("fundAssetDetails.text.copyManager")),1)])),_:1}),f(c,{class:"muntohtd"},{default:d((()=>[h(_(i.form.administrator),1)])),_:1})])),_:1}),f(c,{class:"box2"},{default:d((()=>[f(c,{class:"bumpo"},{default:d((()=>[h(_(t.$t("fundAssetDetails.text.trackingIndex")),1)])),_:1}),f(c,{class:"muntohtd",style:{color:"#5179ED"}},{default:d((()=>[h(_(i.form.trace_title),1)])),_:1}),f(c,{class:"muntohtd",style:{color:"#E5574F","margin-left":"10rpx"}},{default:d((()=>[h(_(i.form.trace_index),1)])),_:1})])),_:1})])),_:1}),f(c,{style:{width:"100%",height:"20rpx","background-color":"#F5F5F5"}}),f(c,{class:"fund_answer"},{default:d((()=>[f(c,{style:{height:"20rpx"}}),f(c,{class:"box1"},{default:d((()=>[f(c,{class:"bukos"},{default:d((()=>[h(_(t.$t("fundAssetDetails.text.fund")),1)])),_:1})])),_:1}),f(c,{style:{width:"100%",height:"20rpx"}}),C("div",{class:"video_box"},[f(x,{controls:"controls",class:"js-parse-amenebmoegbfiohcnmoiaheccgikmfid",src:i.form.video_url},null,8,["src"])]),f(c,{style:{width:"100%",height:"20rpx"}}),f(y,{current:"0",activeColor:"#E5574F",inactiveColor:"#E5574F",direction:"column",dot:"",iconSize:"20"},{default:d((()=>[(l(!0),D(B,null,k(i.form.content_text,((t,e)=>(l(),o(g,{title:t.small_title,desc:t.small_content,key:e},null,8,["title","desc"])))),128))])),_:1}),f(c,{style:{width:"100%",height:"140rpx"}}),f(c,{class:"mudanw"},{default:d((()=>[f(c,{class:"add_optional",onClick:e[0]||(e[0]=t=>n.Collect())},{default:d((()=>[f(p,{src:"data:image/png;base64,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",mode:""}),f(c,{class:"pokubo"},{default:d((()=>[h(_(t.$t("fundAssetDetails.text.addOptional")),1)])),_:1})])),_:1}),f(c,{class:"operation_content",onClick:n.skip},{default:d((()=>[f(c,{class:"kunhop"},{default:d((()=>[h(_(t.$t("fundAssetDetails.text.buyItNow")),1)])),_:1})])),_:1},8,["onClick"])])),_:1})])),_:1})])),_:1})}],["__scopeId","data-v-698ea7fd"]]);export{Q as default};
