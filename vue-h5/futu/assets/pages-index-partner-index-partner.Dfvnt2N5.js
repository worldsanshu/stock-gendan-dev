import{_ as t}from"./uni-nav-bar.CqtfX-i-.js";import{V as e,U as a,x as s,y as l,D as n,H as u,Y as r,A as i,F as _,$ as d,C as p,Z as o,I as m,J as f,E as c,z as y,G as x,N as b,P as h}from"./index-_qVRbLMl.js";import{r as S}from"./uni-app.es.CwLmRD9h.js";import{p as $}from"./index.md09NobF.js";import{_ as k}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CkUbBeV1.js";import"./uni-status-bar.BBpSB2OF.js";const g=""+new URL("partner_rule_head.1bc22a6f-CPYBmPcs.png",import.meta.url).href,v=k(Object.assign({name:"index-partner"},{__name:"index-partner",setup(k){const v=e(),w=a({salary_type:[],subsidy:[],user_level:[],salary_config:[],notes_partner_system:""});return $().then((t=>{w.user_level=t.data.user_level??[],w.salary_type=t.data.salary_type??[],w.subsidy=t.data.subsidy??[],w.salary_config=t.data.salary_config??[],w.notes_partner_system=t.data.notes_partner_system??""})),async function(){const{data:t}=await d();v.value=t.module}(),(e,a)=>{const d=S(s("uni-nav-bar"),t),$=b,k=p,j=h;return l(),n(_,null,[u(d,{title:e.$t("page.title.partnershipSystem"),"status-bar":"","background-color":"#e34a2b",color:"#fff","left-icon":"left",fixed:"",border:!1,onClickLeft:r(o)},null,8,["title","onClickLeft"]),u(k,{class:"parter-page"},{default:i((()=>[u($,{mode:"widthFix",src:g,style:{width:"100%"}}),u(k,{class:"list-box"},{default:i((()=>[u(k,{class:"title"},{default:i((()=>[m(f(e.$t("partnershipSystem.text.illustrate")),1)])),_:1}),u(k,{class:"explain-box"},{default:i((()=>[u(k,{class:"explain-header"},{default:i((()=>[u(k,null,{default:i((()=>[m(f(e.$t("partnershipSystem.text.grade")),1)])),_:1}),u(k,null,{default:i((()=>[m(f(e.$t("partnershipSystem.text.minimumAmount")),1)])),_:1}),u(k,null,{default:i((()=>[m(f(e.$t("partnershipSystem.text.maximumAmount")),1)])),_:1}),u(k,null,{default:i((()=>[m(f(e.$t("partnershipSystem.text.buyPosition")),1)])),_:1}),u(k,null,{default:i((()=>[m(f(e.$t("partnershipSystem.text.teamSize")),1)])),_:1})])),_:1}),(l(!0),n(_,null,c(w.user_level,((t,a)=>(l(),y(k,{class:"list-box",key:a},{default:i((()=>[u(k,null,{default:i((()=>[m(f(t.name),1)])),_:2},1024),u(k,null,{default:i((()=>[m(f(t.min_money),1)])),_:2},1024),u(k,null,{default:i((()=>[m(f(t.max_money),1)])),_:2},1024),u(k,null,{default:i((()=>[u(k,null,{default:i((()=>[m(f(t.position_ratio_txt),1)])),_:2},1024)])),_:2},1024),t.num_work?(l(),y(k,{key:0},{default:i((()=>[m(f(t.num_work),1)])),_:2},1024)):(l(),y(k,{key:1},{default:i((()=>[m(f(t.min_num)+"-"+f(t.max_num)+f(e.$t("common.text.people")),1)])),_:2},1024))])),_:2},1024)))),128))])),_:1})])),_:1}),u(k,{class:"list-box"},{default:i((()=>[u(k,{class:"title"},{default:i((()=>[m(f(w.salary_type[1]),1)])),_:1}),u(k,{class:"content-box"},{default:i((()=>[u(k,{class:"header"},{default:i((()=>[u(k,null,{default:i((()=>[m(f(e.$t("partnershipSystem.text.teamSize")),1)])),_:1}),u(k,null,{default:i((()=>[m(f(e.$t("partnershipSystem.text.partnerLevel")),1)])),_:1}),u(k,null,{default:i((()=>[m(f(e.$t("partnershipSystem.text.teamShareRatio")),1)])),_:1})])),_:1}),(l(!0),n(_,null,c(w.user_level,((t,a)=>(l(),y(k,{class:"list",key:a},{default:i((()=>[t.num_work?(l(),y(k,{key:0},{default:i((()=>[m(f(t.num_work),1)])),_:2},1024)):(l(),y(k,{key:1},{default:i((()=>[m(f(t.min_num)+"~"+f(t.max_num)+" "+f(e.$t("common.text.people")),1)])),_:2},1024)),u(k,null,{default:i((()=>[m(f(t.name),1)])),_:2},1024),u(k,null,{default:i((()=>[m(f(t.ratio)+"%",1)])),_:2},1024)])),_:2},1024)))),128)),u(k,{class:"desc"},{default:i((()=>[m(f(e.$t("partnershipSystem.text.tip1")),1)])),_:1})])),_:1})])),_:1}),v.value&&1===v.value.wage_display?(l(),y(k,{key:0,class:"list-box"},{default:i((()=>[u(k,{class:"title"},{default:i((()=>[m(f(e.$t("partnershipSystem.text.subtitle1")),1)])),_:1}),u(k,{class:"content-box"},{default:i((()=>[u(k,{class:"header"},{default:i((()=>[u(k,null,{default:i((()=>[m(f(e.$t("partnershipSystem.text.serialNumber")),1)])),_:1}),u(k,null,{default:i((()=>[m(f(e.$t("partnershipSystem.text.followUpSalesIncome")),1)])),_:1}),u(k,null,{default:i((()=>[m(f(e.$t("partnershipSystem.text.salaryRewards")),1)])),_:1})])),_:1}),(l(!0),n(_,null,c(w.salary_config,((t,e)=>(l(),y(k,{class:"list",key:e},{default:i((()=>[u(k,null,{default:i((()=>[m(f(t.name),1)])),_:2},1024),u(k,null,{default:i((()=>[m(f(parseInt(t.min_money))+"~"+f(parseInt(t.max_money)),1)])),_:2},1024),u(k,null,{default:i((()=>[m(f(t.ratio)+"%",1)])),_:2},1024)])),_:2},1024)))),128)),u(k,{class:"btn-box"},{default:i((()=>[m(f(e.$t("partnershipSystem.text.subtitle2")),1)])),_:1}),u(k,{class:"desc"},{default:i((()=>[m(f(e.$t("partnershipSystem.text.tip2")),1)])),_:1}),u(k,{class:"btn-box",style:{"margin-top":"0"}},{default:i((()=>[m(f(e.$t("partnershipSystem.text.subtitle4")),1)])),_:1}),u(k,{class:"desc"},{default:i((()=>[m(f(e.$t("partnershipSystem.text.tip3")),1)])),_:1})])),_:1})])),_:1})):x("",!0),u(k,{class:"list-box"},{default:i((()=>[u(k,{class:"title"},{default:i((()=>[m(f(e.$t("partnershipSystem.text.subtitle3")),1)])),_:1}),u(k,{class:"content-box"},{default:i((()=>[u(k,{class:"header"},{default:i((()=>[u(k,null,{default:i((()=>[m(f(e.$t("partnershipSystem.text.numberOfDirectSubordinates")),1)])),_:1}),u(k,null,{default:i((()=>[m(f(e.$t("partnershipSystem.text.partnerSubsidy")),1)])),_:1})])),_:1}),(l(!0),n(_,null,c(w.subsidy,((t,a)=>(l(),y(k,{class:"list",key:a},{default:i((()=>[u(k,null,{default:i((()=>[m(f(t.num)+" "+f(e.$t("common.text.people")),1)])),_:2},1024),u(k,null,{default:i((()=>[m(f(t.ratio),1)])),_:2},1024)])),_:2},1024)))),128)),u(k,{class:"desc"},{default:i((()=>[m(f(e.$t("partnershipSystem.text.tip4")),1)])),_:1})])),_:1})])),_:1}),u(k,{class:"list-box"},{default:i((()=>[u(k,{class:"content-box"},{default:i((()=>[u(k,{class:"btn-box",style:{"margin-top":"0"}},{default:i((()=>[m(f(e.$t("partnershipSystem.text.remark")),1)])),_:1}),u(k,{class:"desc"},{default:i((()=>[u(j,{nodes:w.notes_partner_system},null,8,["nodes"])])),_:1})])),_:1})])),_:1})])),_:1})],64)}}}),[["__scopeId","data-v-91d7e1ba"]]);export{v as default};
