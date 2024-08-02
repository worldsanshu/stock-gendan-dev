import{R as t,V as a,x as e,y as s,D as l,H as i,Y as n,A as o,F as c,q as r,C as u,at as d,f as m,I as p,bX as f,cb as _,E as g,z as k,G as y,cc as b,J as h,bI as v,c2 as x}from"./index-CSlSatai.js";import{_ as C}from"./uni-nav-bar.DfKjGfW-.js";import{d as w,c as j,r as $}from"./uni-app.es.D8i8E1rN.js";import{_ as z}from"./uni-icons.DN3X1e-f.js";import{_ as F}from"./u-image.DJ3RPl7i.js";import{_ as A}from"./u-skeleton.W28y6QJV.js";import{_ as D}from"./u-empty.BFmqDS0a.js";import{_ as I}from"./uni-section.DeN5oXjZ.js";import{i as M,j as q}from"./wallet.DGTkEhaY.js";import{_ as H}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.yUEwlOC_.js";import"./u-icon.AU2CAoS5.js";import"./u-transition.BrDVxV9j.js";const L=H(Object.assign({name:"user-center-wallet-management"},{__name:"user-center-wallet-management",setup(H){const{t:L}=t();let O=a("transparent");const T=a([]),B=a([]),E=a(!0),G=a(!1),J=a(!1);async function N(){const{error:t,data:a,message:e}=await M();t?await r({title:e}):(T.value=a.bank_list,B.value=a.wallet_list,E.value=!1,G.value=!T.value.length,J.value=!B.value.length)}async function P(t,a){x({title:L("common.text.systemHint"),content:L("walletManagement.text.tip1"),success:async e=>{if(e.confirm){const{error:e,message:s}=await q({id:t,type:a});await r({title:s,icon:e?"none":"success"}),e||await N()}}})}return w((t=>{t.scrollTop>20?O.value="#ff7a4d":O.value="transparent"})),j(N),(t,a)=>{const r=$(e("uni-nav-bar"),C),x=u,w=$(e("uni-icons"),z),j=$(e("up-image"),F),M=$(e("up-skeleton"),A),q=$(e("up-empty"),D),H=$(e("uni-section"),I);return s(),l(c,null,[i(r,{title:"资金管理","status-bar":"","background-color":n(O),color:"#fff",fixed:"",border:!1,"left-icon":"left",onClickLeft:n(d)},null,8,["background-color","onClickLeft"]),i(x,{class:"page-bg"}),i(x,{class:"body"},{default:o((()=>[i(x,{class:"page-header"},{default:o((()=>[i(x,{class:"header-option"},{default:o((()=>[i(x,{class:"header-option-item",onClick:n(m)},{default:o((()=>[p("充值")])),_:1},8,["onClick"]),i(x,{class:"header-option-item",onClick:n(f)},{default:o((()=>[p("提现")])),_:1},8,["onClick"]),i(x,{class:"header-option-item header-option-item-active"},{default:o((()=>[p("钱包")])),_:1})])),_:1})])),_:1}),i(H,{title:t.$t("walletManagement.text.myBankCard"),type:"line",padding:""},{right:o((()=>[i(w,{class:"mr-20",type:"plusempty",size:"30",color:"#037CFF",onClick:n(_)},null,8,["onClick"])])),default:o((()=>[i(M,{rows:"6","rows-height":"100",title:!1,isArticle:!1,loading:E.value},{default:o((()=>[i(x,{class:"bank-list"},{default:o((()=>[(s(!0),l(c,null,g(T.value,(t=>(s(),k(x,{class:"bank-list-item",key:t.id},{default:o((()=>[i(j,{class:"bank-list-item__icon",src:"/static/images/union-pay.png",width:"108rpx",height:"67rpx"}),i(x,{class:"bank-list-item__content ml-20"},{default:o((()=>[i(x,{class:"ml-10 font-14-inverse-bold"},{default:o((()=>[p(h(t.bank),1)])),_:2},1024),i(x,{class:"font-14-inverse"},{default:o((()=>[p(h(t.card),1)])),_:2},1024)])),_:2},1024),i(w,{type:"trash",size:"20",color:"#fff",onClick:a=>P(t.id,0)},null,8,["onClick"])])),_:2},1024)))),128))])),_:1})])),_:1},8,["loading"]),G.value?(s(),k(q,{key:0,mode:"data",icon:"/static/empty/empty-list.png",text:t.$t("common.text.noData")},null,8,["text"])):y("",!0)])),_:1},8,["title"]),i(H,{title:t.$t("walletManagement.text.myWalletAddress"),type:"line",padding:""},{right:o((()=>[i(w,{class:"mr-20",type:"plusempty",size:"30",color:"#037CFF",onClick:n(b)},null,8,["onClick"])])),default:o((()=>[i(M,{rows:"6","rows-height":"80",title:!1,isArticle:!1,loading:E.value},{default:o((()=>[i(x,{class:"address-list"},{default:o((()=>[(s(!0),l(c,null,g(B.value,(t=>(s(),k(x,{class:"address-list-item border-bottom",key:t.id},{default:o((()=>[i(j,{class:"address-list-item__icon",src:t.logo,width:"80rpx",height:"80rpx"},null,8,["src"]),i(x,{class:"address-list-item__content ml-20"},{default:o((()=>[i(x,{class:"font-14"},{default:o((()=>[p(h(`${t.name} (${t.alias})`),1)])),_:2},1024),t.address?(s(),k(x,{key:0,class:"font-12 mt-10"},{default:o((()=>[p(h(t.address)+" ",1),i(w,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:a=>n(v)(t.address)},null,8,["onClick"])])),_:2},1024)):y("",!0)])),_:2},1024),i(w,{type:"trash",size:"20",onClick:a=>P(t.id,1)},null,8,["onClick"])])),_:2},1024)))),128))])),_:1}),J.value?(s(),k(q,{key:0,mode:"data",icon:"/static/empty/empty-list.png",text:t.$t("common.text.noData")},null,8,["text"])):y("",!0)])),_:1},8,["loading"])])),_:1},8,["title"])])),_:1})],64)}}}),[["__scopeId","data-v-ab343477"]]);export{L as default};
