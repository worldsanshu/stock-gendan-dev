import{V as e,ap as t,x as l,y as a,z as o,A as s,C as u,H as i,I as n,J as r,aa as d,Y as _,cj as c,G as f,B as m,bV as p,aq as x,$ as g,O as v,P as h}from"./index-CRttwwij.js";import{_ as y}from"./u-icon.CVzxT_4w.js";import{r as w}from"./uni-app.es.otF8raCd.js";import{_ as b}from"./u-image.DAa6LJ_P.js";import{_ as k}from"./u-modal.DE8RaHn4.js";import{h as $,j as C,f as j,a as z}from"./index.CgRNu-Dw.js";import{_ as F}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-transition.B9t9JGP4.js";import"./u-line.C5lrB_C7.js";import"./u-loading-icon.Cvek95z1.js";import"./u-popup.txI2lkEk.js";import"./u-status-bar.BIDoPN9d.js";const O=F({__name:"agent-center",setup(F){const O=e(),T=e({day_income:0,day_recharge_num:0,invest_day:0,invest_total:0,level_text:"",not_invest_num:0,ordinary_level:0,primary_level:0,team_num:0,total_income:0,zhitui_num:0}),B=e(),N=e(),S=e(!1),I=e();function R(){S.value=!0,p({data:N.value+B.value,showToast:!1})}function V(e){x(e)}t((()=>{$().then((e=>{e.data&&(O.value=e.data)})),C().then((e=>{console.log(e),T.value=e.data})),j().then((e=>{B.value=e.data.url,N.value=e.data.share_title})),z().then((e=>{I.value=e.data.module}))}));const A=()=>{x("/pages/record/record")},D=()=>{g()};return(e,t)=>{const p=w(l("up-icon"),y),x=u,g=v,$=w(l("up-image"),b),C=h,j=w(l("up-modal"),k);return a(),o(x,{class:"agent-center-page"},{default:s((()=>[i(x,{class:"header-box"},{default:s((()=>[i(x,{onClick:D},{default:s((()=>[i(p,{name:"arrow-left",size:"38rpx",color:"#F8D8B8",style:{"margin-right":"12rpx"}}),i(x,{style:{color:"#f8d8b8"}},{default:s((()=>[n(r(e.$t("page.title.follow")),1)])),_:1})])),_:1}),i(x,null,{default:s((()=>[n(r(e.$t("follow.text.currentLevel")),1)])),_:1}),i(x,{onClick:A},{default:s((()=>[i(p,{name:"clock",size:"32rpx",color:"#fff",style:{"margin-right":"12rpx"}}),i(x,{style:{"font-size":"26rpx"}},{default:s((()=>[n(r(e.$t("follow.text.orderRecord")),1)])),_:1})])),_:1})])),_:1}),i(x,{class:"agent-center-title"},{default:s((()=>[n(r(T.value.my_level_text),1)])),_:1}),i(x,{class:"upgrade-progress"},{default:s((()=>[i(g,{class:"last-upgrade-item"},{default:s((()=>[n(r(T.value.my_level_text),1)])),_:1}),i(x,{class:"progress-bar-content"},{default:s((()=>[T.value.next_level_text?(a(),o(g,{key:0},{default:s((()=>[n("下一级")])),_:1})):(a(),o(g,{key:1,class:"max"},{default:s((()=>[n("MAX")])),_:1})),i(x,{class:"progress-bar"},{default:s((()=>[i(x,{class:"progress-bar-line",style:d({width:T.value.current_level_people_radio+"%"})},null,8,["style"])])),_:1}),T.value.next_level_text?(a(),o(g,{key:2},{default:s((()=>[n("距升级还需邀请"+r(T.value.next_level_people)+"人",1)])),_:1})):(a(),o(g,{key:3},{default:s((()=>[n("您已升级至"+r(T.value.max_level_text),1)])),_:1}))])),_:1}),T.value.next_level_text?(a(),o(x,{key:0,class:"next-upgrade-item"},{default:s((()=>[n(r(T.value.next_level_text),1)])),_:1})):(a(),o(x,{key:1,class:"next-upgrade-item"},{default:s((()=>[n(r(T.value.max_level_text),1)])),_:1}))])),_:1}),i(x,{class:m(["header-list-box",{"header-list-box-120":I.value&&1===I.value.trader_list_type}])},{default:s((()=>[I.value&&0===I.value.trader_list_type?(a(),o(x,{key:0,onClick:t[0]||(t[0]=e=>_(c)({type:0}))},{default:s((()=>[i($,{model:"widthFix",src:"../../static/images/edit2.png",width:"46rpx",height:"46rpx"}),i(g,null,{default:s((()=>[n(r(e.$t("follow.text.dailyCopy")),1)])),_:1})])),_:1})):f("",!0),I.value&&0===I.value.trader_list_type?(a(),o(x,{key:1,onClick:t[1]||(t[1]=e=>_(c)({type:2}))},{default:s((()=>[i($,{model:"widthFix",src:"../../static/images/edit3.png",width:"46rpx",height:"46rpx"}),i(g,null,{default:s((()=>[n(r(e.$t("follow.text.vipFollower")),1)])),_:1})])),_:1})):f("",!0),I.value&&1===I.value.partner?(a(),o(x,{key:2,onClick:t[2]||(t[2]=e=>V("/pages/index-partner/index-partner"))},{default:s((()=>[i($,{model:"widthFix",src:"../../static/images/edit4.png",width:"46rpx",height:"46rpx"}),i(g,null,{default:s((()=>[n(r(e.$t("follow.text.partnershipSystem")),1)])),_:1})])),_:1})):f("",!0),i(x,{onClick:t[3]||(t[3]=e=>V("/pages/partner-income/partner-income"))},{default:s((()=>[i($,{model:"widthFix",src:"../../static/images/edit1.png",width:"46rpx",height:"46rpx"}),i(g,null,{default:s((()=>[n(r(e.$t("follow.text.partnerIncome")),1)])),_:1})])),_:1})])),_:1},8,["class"]),i(x,{class:"content-box"},{default:s((()=>[i(x,{class:"content-header"},{default:s((()=>[i(x,{class:"content-title"},{default:s((()=>[n(r(e.$t("follow.text.personalReport")),1)])),_:1}),i(x,{class:"report-box"},{default:s((()=>[i(x,{class:"info-box"},{default:s((()=>[i(g,{style:{"font-weight":"300"}},{default:s((()=>[n(r(e.$t("follow.text.totalRevenue"))+"（"+r(e.$t("common.text.currencyUnit"))+"）",1)])),_:1}),i(g,null,{default:s((()=>[n(r(T.value.my_income_total),1)])),_:1}),i(x,{class:"btn-box",onClick:t[4]||(t[4]=e=>V("/pages/today/today?type=1"))},{default:s((()=>[i(x,null,{default:s((()=>[n(r(e.$t("follow.text.todayCopyNumber")),1)])),_:1}),i(x,null,{default:s((()=>[n(r(T.value.my_invest_today),1)])),_:1})])),_:1})])),_:1}),i(x,{class:"info-box"},{default:s((()=>[i(g,{style:{"font-weight":"300"}},{default:s((()=>[n(r(e.$t("follow.text.todayEarnings")),1)])),_:1}),T.value.my_income_today?T.value.my_income_today>0?(a(),o(g,{key:1},{default:s((()=>[n(r(T.value.my_income_today?`+${T.value.my_income_today}`:"0"),1)])),_:1})):(a(),o(g,{key:2},{default:s((()=>[n(r(T.value.my_income_today?`${T.value.my_income_today}`:"0"),1)])),_:1})):(a(),o(g,{key:0},{default:s((()=>[n("0")])),_:1})),i(x,{class:"btn-box",onClick:t[5]||(t[5]=e=>V("/pages/documentary-total/documentary-total"))},{default:s((()=>[i(x,null,{default:s((()=>[n(r(e.$t("follow.text.totalNumberOfOrders")),1)])),_:1}),i(x,null,{default:s((()=>[n(r(T.value.my_invest_total),1)])),_:1})])),_:1})])),_:1})])),_:1})])),_:1}),i(x,{class:"content-title"},{default:s((()=>[n(r(e.$t("follow.text.myTeam")),1)])),_:1}),i(x,{class:"team-box",onClick:t[6]||(t[6]=e=>V("/pages/partner-income/partner-income"))},{default:s((()=>[i(x,null,{default:s((()=>[i($,{src:"../../static/images/edit8.png",width:"46rpx",height:"46rpx",mode:"widthFix"}),i(g,null,{default:s((()=>[n(r(e.$t("follow.text.teamBenefits")),1)])),_:1})])),_:1}),i(x,null,{default:s((()=>[i(x,null,{default:s((()=>[n(r(T.value.team_income),1)])),_:1}),i(p,{name:"arrow-right",size:"32rpx",color:"#97979782"})])),_:1})])),_:1}),i(x,{class:"team-box",onClick:t[7]||(t[7]=e=>V("/pages/group-size/group-size"))},{default:s((()=>[i(x,null,{default:s((()=>[i($,{src:"../../static/images/edit5.png",width:"46rpx",height:"46rpx",mode:"widthFix"}),i(g,null,{default:s((()=>[n(r(e.$t("follow.text.teamSize")),1)])),_:1})])),_:1}),i(x,null,{default:s((()=>[i(x,null,{default:s((()=>[n(r(T.value.team_num_total),1)])),_:1}),i(p,{name:"arrow-right",size:"32rpx",color:"#97979782"})])),_:1})])),_:1}),i(x,{class:"team-box",onClick:t[8]||(t[8]=e=>V("/pages/direct-number/direct-number"))},{default:s((()=>[i(x,null,{default:s((()=>[i($,{src:"../../static/images/edit6.png",width:"46rpx",height:"46rpx",mode:"widthFix"}),i(g,null,{default:s((()=>[n(r(e.$t("follow.text.numberOfDirectSubordinates")),1)])),_:1})])),_:1}),i(x,null,{default:s((()=>[i(x,null,{default:s((()=>[n(r(T.value.team_num_directly),1)])),_:1}),i(p,{name:"arrow-right",size:"32rpx",color:"#97979782"})])),_:1})])),_:1}),i(x,{class:"team-box",onClick:t[9]||(t[9]=e=>V("/pages/week-list/week-list"))},{default:s((()=>[i(x,null,{default:s((()=>[i($,{src:"../../static/images/edit8.png",width:"46rpx",height:"46rpx",mode:"widthFix"}),i(g,null,{default:s((()=>[n(r(e.$t("follow.text.numberOfFollowersThisWeek")),1)])),_:1})])),_:1}),i(x,null,{default:s((()=>[i(x,null,{default:s((()=>[n(r(T.value.team_invest_week),1)])),_:1}),i(p,{name:"arrow-right",size:"32rpx",color:"#97979782"})])),_:1})])),_:1}),i(x,{class:"team-box",onClick:t[10]||(t[10]=e=>V("/pages/today/today?type=2"))},{default:s((()=>[i(x,null,{default:s((()=>[i($,{src:"../../static/images/edit8.png",width:"46rpx",height:"46rpx",mode:"widthFix"}),i(g,null,{default:s((()=>[n(r(e.$t("follow.text.todayCopyNumber")),1)])),_:1})])),_:1}),i(x,null,{default:s((()=>[i(x,null,{default:s((()=>[n(r(T.value.team_invest_today),1)])),_:1}),i(p,{name:"arrow-right",size:"32rpx",color:"#97979782"})])),_:1})])),_:1}),i(x,{class:"team-box",onClick:t[11]||(t[11]=e=>V("/pages/first-charge/first-charge"))},{default:s((()=>[i(x,null,{default:s((()=>[i($,{src:"../../static/images/edit7.png",width:"46rpx",height:"46rpx",mode:"widthFix"}),i(g,null,{default:s((()=>[n(r(e.$t("follow.text.firstChargeToday")),1)])),_:1})])),_:1}),i(x,null,{default:s((()=>[i(x,null,{default:s((()=>[n(r(T.value.team_recharge_today),1)])),_:1}),i(p,{name:"arrow-right",size:"32rpx",color:"#97979782"})])),_:1})])),_:1}),i(x,{class:"content-bar",onClick:R},{default:s((()=>[n(r(e.$t("follow.text.inviteNow")),1)])),_:1}),O.value?(a(),o(x,{key:0,class:"content-title"},{default:s((()=>[n(r(O.value.partner_notice),1)])),_:1})):f("",!0),O.value?(a(),o(x,{key:1,class:"tips-box"},{default:s((()=>[i(C,{nodes:O.value.partner_notice_content},null,8,["nodes"])])),_:1})):f("",!0)])),_:1}),i(j,{show:S.value,title:e.$t("follow.text.tip6"),onClose:t[13]||(t[13]=e=>S.value=!1),onCancel:t[14]||(t[14]=e=>S.value=!1),onConfirm:t[15]||(t[15]=e=>S.value=!1),showConfirmButton:!1},{default:s((()=>[i(x,{class:"recommended-modal-box"},{default:s((()=>[i(x,null,{default:s((()=>[n(r(N.value),1)])),_:1}),i(x,null,{default:s((()=>[n(r(e.$t("follow.text.inviteLink"))+r(B.value),1)])),_:1}),i(x,{class:"modal-footer"},{default:s((()=>[i(x,{class:"btn",onClick:t[12]||(t[12]=e=>S.value=!1)},{default:s((()=>[n(r(e.$t("follow.text.clickClose")),1)])),_:1})])),_:1})])),_:1})])),_:1},8,["show","title"])])),_:1})}}},[["__scopeId","data-v-bcdc0b97"]]);export{O as default};
