import{_ as t}from"./uni-nav-bar.V8U-xaMC.js";import{R as e,V as a,ap as o,x as l,y as s,D as n,H as r,Y as u,A as i,F as m,C as c,Z as d,I as p,J as f,E as _,z as v,G as x,B as g}from"./index-Br34eEhS.js";import{r as y}from"./uni-app.es.-KBXnIhR.js";import{_ as b}from"./u-tabs.BOAkDDv2.js";import{_ as h}from"./u-loadmore.RbKvhosc.js";import{_ as j}from"./u-empty.B7Sg4x7o.js";import{N as I,a as k}from"./index.DFLHdVnw.js";import{_ as $}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.DfW4o02-.js";import"./uni-status-bar.CFG8IPV-.js";import"./u-line.B9GtlKhw.js";import"./u-loading-icon.DBZG26k-.js";import"./u-icon.B55BycVW.js";const C=$({__name:"partner-income",setup($){const{t:C}=e(),w=a(1),D=a([]),M=a(1),S=a("loadmore"),T=a(0),F=a([]);function H(t){T.value=t.type,D.value=[],L()}o((()=>{L()}));const L=()=>{I({page:w.value,type:T.value}).then((t=>{for(let e=0;e<t.data.data.length;e++)D.value.push(t.data.data[e]);M.value=t.data.last_page,0==t.data.data.length&&(S.value="nomore")}))},N=()=>{w.value>=M.value&&(S.value="nomore"),w.value>=M.value||(S.value="loading",w.value++,setTimeout((()=>{L()}),0))};return async function(){const t=await k();let e=[];t.data&&(e=[{name:C("partnerIncome.text.all"),type:0},{name:C("partnerIncome.text.teamDivision"),type:1},{name:C("partnerIncome.text.subsidy"),type:3}],1===t.data.module.wage_display&&e.push({name:C("partnerIncome.text.weeklySalary"),type:2}),F.value=e,this.Collect())}(),(e,a)=>{const o=y(l("uni-nav-bar"),t),I=y(l("up-tabs"),b),k=c,$=y(l("up-loadmore"),h),C=y(l("up-empty"),j);return s(),n(m,null,[r(o,{title:e.$t("page.title.partnerIncome"),"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left",fixed:"",border:!1,onClickLeft:u(d)},null,8,["title","onClickLeft"]),r(k,{class:"root"},{default:i((()=>[r(I,{list:F.value,onClick:H,scrollable:!1},null,8,["list"]),r(k,{class:"fixed"},{default:i((()=>[r(k,{class:"header border-bottom"},{default:i((()=>[r(k,{class:"header-item font-14"},{default:i((()=>[p(f(e.$t("partnerIncome.text.dataSent")),1)])),_:1}),r(k,{class:"header-item font-14"},{default:i((()=>[p(f(e.$t("partnerIncome.text.transactionType")),1)])),_:1}),r(k,{class:"header-item font-14"},{default:i((()=>[p(f(e.$t("partnerIncome.text.amount")),1)])),_:1})])),_:1})])),_:1}),r(k,{class:"list"},{default:i((()=>[(s(!0),n(m,null,_(D.value,((t,e)=>(s(),v(k,{class:"list-item border-bottom",key:e},{default:i((()=>[r(k,{class:"list-item-col"},{default:i((()=>[r(k,{class:"font-12-grey"},{default:i((()=>{return[p(f((e=t.create_time,a=new Date(1e3*e),a.getFullYear()+"-"+(a.getMonth()+1)+"-"+a.getDate()+" "+a.getHours()+":"+a.getMinutes())),1)];var e,a})),_:2},1024)])),_:2},1024),r(k,{class:"list-item-col font-14"},{default:i((()=>[p(f(t.type_name),1)])),_:2},1024),r(k,{class:g(["list-item-col font-14-red",{"down-color":t.price<0}])},{default:i((()=>[p(f(t.price),1)])),_:2},1032,["class"])])),_:2},1024)))),128))])),_:1}),D.value.length>0?(s(),v($,{key:0,status:S.value,"loading-text":e.$t("common.text.loading"),"loadmore-text":e.$t("common.text.loadMore"),"nomore-text":e.$t("common.text.noMore"),onClick:N},null,8,["status","loading-text","loadmore-text","nomore-text"])):x("",!0),0==D.value.length?(s(),v(C,{key:1,mode:"data",icon:"/static/empty/empty-data.png",text:e.$t("common.text.noData")},null,8,["text"])):x("",!0)])),_:1})],64)}}},[["__scopeId","data-v-3bc1ab9c"]]);export{C as default};
