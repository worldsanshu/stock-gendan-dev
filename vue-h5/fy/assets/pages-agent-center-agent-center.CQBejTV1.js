import{V as e,ap as t,x as l,y as a,z as o,B as u,C as s,H as n,I as i,J as r,a9 as d,Y as _,cl as c,G as f,A as m,bX as p,aq as x,bt as v,O as g,P as y}from"./index-DugkB_-4.js";import{_ as h}from"./u-icon.Ca1c56qc.js";import{r as w}from"./uni-app.es.CX-CC-lz.js";import{_ as k}from"./u-image.1MCDMfbz.js";import{_ as b}from"./u-modal.DgcFYL2i.js";import{h as C,j as $,f as z,a as F}from"./index.CCikdCXX.js";import{_ as j}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-transition.bUAwPu-p.js";import"./u-line.BpwwmqXn.js";import"./u-loading-icon.CCS9DSWM.js";import"./u-popup.D4xZoMdU.js";import"./u-status-bar.CdB_NfJN.js";const B=j({__name:"agent-center",setup(j){const B=e(),O=e({day_income:0,day_recharge_num:0,invest_day:0,invest_total:0,level_text:"",not_invest_num:0,ordinary_level:0,primary_level:0,team_num:0,total_income:0,zhitui_num:0}),N=e(),T=e(),A=e(!1),D=e();function I(){A.value=!0,p({data:T.value+N.value,showToast:!1})}function R(e){x(e)}t((()=>{C().then((e=>{e.data&&(B.value=e.data)})),$().then((e=>{console.log(e),O.value=e.data})),z().then((e=>{N.value=e.data.url,T.value=e.data.share_title})),F().then((e=>{D.value=e.data.module}))}));const S=()=>{x("/pages/record/record")},E=()=>{v()};return(e,t)=>{const p=w(l("up-icon"),h),x=u,v=g,C=w(l("up-image"),k),$=y,z=w(l("up-modal"),b);return s(),a(x,{class:"agent-center-page"},{default:o((()=>[n(x,{class:"header-box"},{default:o((()=>[n(x,{onClick:E},{default:o((()=>[n(p,{name:"arrow-left",size:"38rpx",color:"#F8D8B8",style:{"margin-right":"12rpx"}}),n(x,{style:{color:"#f8d8b8"}},{default:o((()=>[i(r(e.$t("page.title.follow")),1)])),_:1})])),_:1}),n(x,null,{default:o((()=>[i(r(e.$t("follow.text.currentLevel")),1)])),_:1}),n(x,{onClick:S},{default:o((()=>[n(p,{name:"clock",size:"32rpx",color:"#fff",style:{"margin-right":"12rpx"}}),n(x,{style:{"font-size":"26rpx"}},{default:o((()=>[i(r(e.$t("follow.text.orderRecord")),1)])),_:1})])),_:1})])),_:1}),n(x,{class:"agent-center-title"},{default:o((()=>[i(r(O.value.my_level_text),1)])),_:1}),n(x,{class:"upgrade-progress"},{default:o((()=>[n(v,{class:"last-upgrade-item"},{default:o((()=>[i(r(O.value.my_level_text),1)])),_:1}),n(x,{class:"progress-bar-content"},{default:o((()=>[O.value.next_level_text?(s(),a(v,{key:0},{default:o((()=>[i("下一级")])),_:1})):(s(),a(v,{key:1,class:"max"},{default:o((()=>[i("MAX")])),_:1})),n(x,{class:"progress-bar"},{default:o((()=>[n(x,{class:"progress-bar-line",style:d({width:O.value.current_level_people_radio+"%"})},null,8,["style"])])),_:1}),O.value.next_level_text?(s(),a(v,{key:2},{default:o((()=>[i("距升级还需邀请"+r(O.value.next_level_people)+"人",1)])),_:1})):(s(),a(v,{key:3},{default:o((()=>[i("您已升级至"+r(O.value.max_level_text),1)])),_:1}))])),_:1}),O.value.next_level_text?(s(),a(x,{key:0,class:"next-upgrade-item"},{default:o((()=>[i(r(O.value.next_level_text),1)])),_:1})):(s(),a(x,{key:1,class:"next-upgrade-item"},{default:o((()=>[i(r(O.value.max_level_text),1)])),_:1}))])),_:1}),n(x,{class:m(["header-list-box",{"header-list-box-120":D.value&&1===D.value.trader_list_type}])},{default:o((()=>[D.value&&0===D.value.trader_list_type?(s(),a(x,{key:0,onClick:t[0]||(t[0]=e=>_(c)({type:0}))},{default:o((()=>[n(C,{model:"widthFix",src:"../../static/images/edit2.png",width:"46rpx",height:"46rpx"}),n(v,null,{default:o((()=>[i(r(e.$t("follow.text.dailyCopy")),1)])),_:1})])),_:1})):f("",!0),D.value&&0===D.value.trader_list_type?(s(),a(x,{key:1,onClick:t[1]||(t[1]=e=>_(c)({type:2}))},{default:o((()=>[n(C,{model:"widthFix",src:"../../static/images/edit3.png",width:"46rpx",height:"46rpx"}),n(v,null,{default:o((()=>[i(r(e.$t("follow.text.vipFollower")),1)])),_:1})])),_:1})):f("",!0),D.value&&1===D.value.partner?(s(),a(x,{key:2,onClick:t[2]||(t[2]=e=>R("/pages/index-partner/index-partner"))},{default:o((()=>[n(C,{model:"widthFix",src:"../../static/images/edit4.png",width:"46rpx",height:"46rpx"}),n(v,null,{default:o((()=>[i(r(e.$t("follow.text.partnershipSystem")),1)])),_:1})])),_:1})):f("",!0),D.value&&1===D.value.partner?(s(),a(x,{key:3,onClick:t[3]||(t[3]=e=>R("/pages/partner-income/partner-income"))},{default:o((()=>[n(C,{model:"widthFix",src:"../../static/images/edit1.png",width:"46rpx",height:"46rpx"}),n(v,null,{default:o((()=>[i(r(e.$t("follow.text.partnerIncome")),1)])),_:1})])),_:1})):f("",!0)])),_:1},8,["class"]),n(x,{class:"content-box"},{default:o((()=>[n(x,{class:"content-header"},{default:o((()=>[n(x,{class:"content-title"},{default:o((()=>[i(r(e.$t("follow.text.personalReport")),1)])),_:1}),n(x,{class:"report-box"},{default:o((()=>[n(x,{class:"info-box"},{default:o((()=>[n(v,{style:{"font-weight":"300"}},{default:o((()=>[i(r(e.$t("follow.text.totalRevenue"))+"（"+r(e.$t("common.text.currencyUnit"))+"）",1)])),_:1}),n(v,null,{default:o((()=>[i(r(O.value.my_income_total),1)])),_:1}),n(x,{class:"btn-box",onClick:t[4]||(t[4]=e=>R("/pages/today/today?type=1"))},{default:o((()=>[n(x,null,{default:o((()=>[i(r(e.$t("follow.text.todayCopyNumber")),1)])),_:1}),n(x,null,{default:o((()=>[i(r(O.value.my_invest_today<0?0:O.value.my_invest_today),1)])),_:1})])),_:1})])),_:1}),n(x,{class:"info-box"},{default:o((()=>[n(v,{style:{"font-weight":"300"}},{default:o((()=>[i(r(e.$t("follow.text.todayEarnings")),1)])),_:1}),O.value.my_income_today?O.value.my_income_today>0?(s(),a(v,{key:1},{default:o((()=>[i(r(O.value.my_income_today?`+${O.value.my_income_today}`:"0"),1)])),_:1})):(s(),a(v,{key:2},{default:o((()=>[i(r(O.value.my_income_today?`${O.value.my_income_today}`:"0"),1)])),_:1})):(s(),a(v,{key:0},{default:o((()=>[i("0")])),_:1})),n(x,{class:"btn-box",onClick:t[5]||(t[5]=e=>R("/pages/documentary-total/documentary-total"))},{default:o((()=>[n(x,null,{default:o((()=>[i(r(e.$t("follow.text.totalNumberOfOrders")),1)])),_:1}),n(x,null,{default:o((()=>[i(r(O.value.my_invest_total<0?0:O.value.my_invest_total),1)])),_:1})])),_:1})])),_:1})])),_:1})])),_:1}),n(x,{class:"content-title"},{default:o((()=>[i(r(e.$t("follow.text.myTeam")),1)])),_:1}),n(x,{class:"team-box",onClick:t[6]||(t[6]=e=>R("/pages/first-charge/first-charge"))},{default:o((()=>[n(x,null,{default:o((()=>[n(C,{src:"../../static/images/edit7.png",width:"46rpx",height:"46rpx",mode:"widthFix"}),n(v,null,{default:o((()=>[i(r(e.$t("follow.text.firstChargeToday")),1)])),_:1})])),_:1}),n(x,null,{default:o((()=>[n(x,null,{default:o((()=>[i(r(O.value.team_recharge_today<0?0:O.value.team_recharge_today),1)])),_:1}),n(p,{name:"arrow-right",size:"32rpx",color:"#97979782"})])),_:1})])),_:1}),n(x,{class:"team-box",onClick:t[7]||(t[7]=e=>R("/pages/direct-number/direct-number"))},{default:o((()=>[n(x,null,{default:o((()=>[n(C,{src:"../../static/images/edit6.png",width:"46rpx",height:"46rpx",mode:"widthFix"}),n(v,null,{default:o((()=>[i(r(e.$t("follow.text.numberOfDirectSubordinates")),1)])),_:1})])),_:1}),n(x,null,{default:o((()=>[n(x,null,{default:o((()=>[i(r(O.value.team_num_directly<0?0:O.value.team_num_directly),1)])),_:1}),n(p,{name:"arrow-right",size:"32rpx",color:"#97979782"})])),_:1})])),_:1}),n(x,{class:"team-box",onClick:t[8]||(t[8]=e=>R("/pages/group-size/group-size"))},{default:o((()=>[n(x,null,{default:o((()=>[n(C,{src:"../../static/images/edit5.png",width:"46rpx",height:"46rpx",mode:"widthFix"}),n(v,null,{default:o((()=>[i(r(e.$t("follow.text.teamSize")),1)])),_:1})])),_:1}),n(x,null,{default:o((()=>[n(x,null,{default:o((()=>[i(r(O.value.team_num_total<0?0:O.value.team_num_total),1)])),_:1}),n(p,{name:"arrow-right",size:"32rpx",color:"#97979782"})])),_:1})])),_:1}),n(x,{class:"team-box",onClick:t[9]||(t[9]=e=>R("/pages/partner-income/partner-income"))},{default:o((()=>[n(x,null,{default:o((()=>[n(C,{src:"../../static/images/edit8.png",width:"46rpx",height:"46rpx",mode:"widthFix"}),n(v,null,{default:o((()=>[i(r(e.$t("follow.text.teamBenefits")),1)])),_:1})])),_:1}),n(x,null,{default:o((()=>[n(x,null,{default:o((()=>[i(r(O.value.team_income),1)])),_:1}),n(p,{name:"arrow-right",size:"32rpx",color:"#97979782"})])),_:1})])),_:1}),n(x,{class:"team-box",onClick:t[10]||(t[10]=e=>R("/pages/week-list/week-list"))},{default:o((()=>[n(x,null,{default:o((()=>[n(C,{src:"../../static/images/edit8.png",width:"46rpx",height:"46rpx",mode:"widthFix"}),n(v,null,{default:o((()=>[i(r(e.$t("follow.text.numberOfFollowersThisWeek")),1)])),_:1})])),_:1}),n(x,null,{default:o((()=>[n(x,null,{default:o((()=>[i(r(O.value.team_invest_week<0?0:O.value.team_invest_week),1)])),_:1}),n(p,{name:"arrow-right",size:"32rpx",color:"#97979782"})])),_:1})])),_:1}),n(x,{class:"team-box",onClick:t[11]||(t[11]=e=>R("/pages/today/today?type=2"))},{default:o((()=>[n(x,null,{default:o((()=>[n(C,{src:"../../static/images/edit8.png",width:"46rpx",height:"46rpx",mode:"widthFix"}),n(v,null,{default:o((()=>[i(r(e.$t("follow.text.todayCopyNumber")),1)])),_:1})])),_:1}),n(x,null,{default:o((()=>[n(x,null,{default:o((()=>[i(r(O.value.team_invest_today<0?0:O.value.team_invest_today),1)])),_:1}),n(p,{name:"arrow-right",size:"32rpx",color:"#97979782"})])),_:1})])),_:1}),n(x,{class:"invest-tip"},{default:o((()=>[n(v,null,{default:o((()=>[i(r("本周优投人数：本周下单时间为准\n今日优投数：已下单时间到结束时间为准"))])),_:1})])),_:1}),n(x,{class:"content-bar",onClick:I},{default:o((()=>[i(r(e.$t("follow.text.inviteNow")),1)])),_:1}),B.value?(s(),a(x,{key:0,class:"content-title"},{default:o((()=>[i(r(B.value.partner_notice),1)])),_:1})):f("",!0),B.value?(s(),a(x,{key:1,class:"tips-box"},{default:o((()=>[n($,{nodes:B.value.partner_notice_content},null,8,["nodes"])])),_:1})):f("",!0)])),_:1}),n(z,{show:A.value,title:e.$t("follow.text.tip6"),onClose:t[13]||(t[13]=e=>A.value=!1),onCancel:t[14]||(t[14]=e=>A.value=!1),onConfirm:t[15]||(t[15]=e=>A.value=!1),showConfirmButton:!1},{default:o((()=>[n(x,{class:"recommended-modal-box"},{default:o((()=>[n(x,null,{default:o((()=>[i(r(T.value),1)])),_:1}),n(x,null,{default:o((()=>[i(r(e.$t("follow.text.inviteLink"))+r(N.value),1)])),_:1}),n(x,{class:"modal-footer"},{default:o((()=>[n(x,{class:"btn",onClick:t[12]||(t[12]=e=>A.value=!1)},{default:o((()=>[i(r(e.$t("follow.text.clickClose")),1)])),_:1})])),_:1})])),_:1})])),_:1},8,["show","title"])])),_:1})}}},[["__scopeId","data-v-818f42d8"]]);export{B as default};
