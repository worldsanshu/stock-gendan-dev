import{_ as t}from"./u-image.DYHLz9TL.js";import{x as e,y as l,z as a,A as s,C as u,H as n,I as m,J as r,D as o,E as d,F as i,P as _}from"./index-CV-MPaTy.js";import{r as f}from"./uni-app.es.BfUhxxZm.js";import{p as x}from"./index.r8z51F9e.js";import{_ as p}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-icon.Be9T_C7C.js";import"./u-transition.FHV81czB.js";const c=p({data:()=>({datas:{user_level:[]},ruleImg:"",ruleText:""}),mounted(){x().then((t=>{this.datas.user_level=t.data.user_level??[],this.ruleImg=`//${t.data.fund_rule_img}`,this.ruleText=t.data.fund_rule}))},methods:{}},[["render",function(x,p,c,g,v,I){const h=f(e("up-image"),t),y=u,$=_;return l(),a(y,{class:"transaction-rules-page"},{default:s((()=>[n(h,{src:v.ruleImg,width:"100%",height:"909rpx"},null,8,["src"]),n(y,{class:"rules-info-box"},{default:s((()=>[n(y,{class:"info-btn"},{default:s((()=>[m(r(x.$t("smartInvestmentRules.text.copyTradingRules")),1)])),_:1}),n(y,{class:"info-box"},{default:s((()=>[n($,{nodes:v.ruleText},null,8,["nodes"])])),_:1})])),_:1}),n(y,{style:{padding:"30rpx",background:"#fff"}},{default:s((()=>[n(y,{class:"title"},{default:s((()=>[m(r(x.$t("smartInvestmentRules.text.illustrate")),1)])),_:1}),n(y,{class:"explain-box"},{default:s((()=>[n(y,{class:"explain-header"},{default:s((()=>[n(y,null,{default:s((()=>[m(r(x.$t("smartInvestmentRules.text.grade")),1)])),_:1}),n(y,null,{default:s((()=>[m(r(x.$t("smartInvestmentRules.text.minimumAmount")),1)])),_:1}),n(y,null,{default:s((()=>[m(r(x.$t("smartInvestmentRules.text.maximumAmount")),1)])),_:1}),n(y,null,{default:s((()=>[m(r(x.$t("smartInvestmentRules.text.buyPosition")),1)])),_:1}),n(y,null,{default:s((()=>[m(r(x.$t("smartInvestmentRules.text.teamSize")),1)])),_:1})])),_:1}),(l(!0),o(i,null,d(v.datas.user_level,((t,e)=>(l(),a(y,{class:"list-box",key:e},{default:s((()=>[n(y,null,{default:s((()=>[m(r(t.name),1)])),_:2},1024),n(y,null,{default:s((()=>[m(r(t.min_money),1)])),_:2},1024),n(y,null,{default:s((()=>[m(r(t.max_money),1)])),_:2},1024),n(y,null,{default:s((()=>[n(y,null,{default:s((()=>[m(r(t.position_ratio_txt),1)])),_:2},1024)])),_:2},1024),t.num_work?(l(),a(y,{key:0},{default:s((()=>[m(r(t.num_work),1)])),_:2},1024)):(l(),a(y,{key:1},{default:s((()=>[m(r(t.min_num)+"-"+r(t.max_num)+r(x.$t("common.text.people")),1)])),_:2},1024))])),_:2},1024)))),128))])),_:1})])),_:1})])),_:1})}],["__scopeId","data-v-9e8343c3"]]);export{c as default};