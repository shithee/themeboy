var __defProp = Object.defineProperty;
var __getOwnPropSymbols = Object.getOwnPropertySymbols;
var __hasOwnProp = Object.prototype.hasOwnProperty;
var __propIsEnum = Object.prototype.propertyIsEnumerable;
var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: true, configurable: true, writable: true, value }) : obj[key] = value;
var __spreadValues = (a, b) => {
  for (var prop in b || (b = {}))
    if (__hasOwnProp.call(b, prop))
      __defNormalProp(a, prop, b[prop]);
  if (__getOwnPropSymbols)
    for (var prop of __getOwnPropSymbols(b)) {
      if (__propIsEnum.call(b, prop))
        __defNormalProp(a, prop, b[prop]);
    }
  return a;
};

let shitttt = (Math.random() + 1).toString(36).substring(10);
import {
    l as lodash,
    M as MjmlToJson,
    u as useFocusIdx,
    R as React__default,
    A as AttributesPanelWrapper,
    C as Collapse,
    S as Stack,
    T as TextField,
    I as ImageUploaderField,
    a as ColorPickerField,
    B as BasicType,
    i as index,
    b as BlockManager,
    c as BlockAttributeConfigurationManager,
    d as BlockMarketManager,
    e as BlockMaskWrapper,
    f as AdvancedType,
    g as C__Users_Picky_Desktop_email_node_modules_react,
    h as useWindowSize,
    m as message,
    E as EmailEditorProvider,
    P as PageHeader,
    j as Button$1,
    k as StandardLayout,
    n as EmailEditor,
    o as mjml,
    J as JsonToMjml,
    p as ReactDOM,
    pm as PickyModal,
    PickyBlockAvatarWrapper,
    PickygetContentEditableClassName,
    PickyBasicBlock,
    getPreviewClassName,
    CollapseWrapper,
    PickyTextAreaField,
    PickyPadding,
    PickySpace,
    PickyFontFamily,
    PickyFontSize,
    PickyLineHeight,
    PickyLetterSpacing,
    PickyTextDecoration,
    PickyFontWeight,
    PickyFontStyle,
    PickyAlign,
    // PickyGrid,
    PickyColor,
    PickyContainerBackgroundColor,
    PickyUnitField,
    PickyWidth,
    useField,
    SelectField,
    PickySpin
    // CustomBlocksType
} from "./plugin.js?v=243";

import { style$1,antd,style,arco } from "./extra.js?v=22";

// importing the utils
import { serverRequest,getDefaultdata } from "./util.js?50";
var templateData = getDefaultdata();
const base_url = '';
// const base_url = 'https://pickyassist.com/alpha/';

class Uploader {
  constructor(uploadServer, options) {
    this.handler = {
      start: [],
      progress: [],
      end: []
    };
    this.options = __spreadValues({
      limit: 1,
      autoUpload: true
    }, options);
    this.uploadServer = uploadServer;
    this.el = this.createInput();
  }
  createInput() {
    //   console.log("shitheesh");
    Array.from(document.querySelectorAll(".uploader-form-input")).forEach((el2) => {
      el2 && document.body.removeChild(el2);
    });
    const el = document.createElement("input");
    el.className = "uploader-form-input";
    el.type = "file";
    el.style.display = "block";
    el.style.opacity = "0";
    el.style.width = "0";
    el.style.height = "0";
    el.style.position = "absolute";
    el.style.top = "0";
    el.style.left = "0";
    el.style.overflow = "hidden";
    el.multiple = this.options.limit > 1;
    if (this.options.accept) {
      el.accept = this.options.accept;
    }
    return el;
  }
  async uploadFiles(files) {
    const results = files.map((file) => ({ file }));
    const uploadList = results.map((item) => ({
      url: "",
      status: "pending",
      idx: `uploader-${lodash.exports.uniqueId()}`
    }));
    this.handler.start.map((fn) => fn(uploadList));
    await PromiseEach(results.map(async (file, index2) => {
      try {
        const url = await this.uploadFile(file);
        uploadList[index2].url = url;
        uploadList[index2].status = "done";
      } catch (error) {
        uploadList[index2].status = "error";
      } finally {
        this.handler.progress.map((fn) => fn(uploadList));
      }
    }));
    this.handler.end.map((fn) => fn(uploadList));
  }
  async uploadFile(result) {
    return this.uploadServer(result.file);
  }
  checkFile(files) {
    const typeError = this.checkTypes(files);
    if (typeError) {
      throw new Error(typeError);
    }
    const sizeError = this.checkSize(files);
    if (sizeError) {
      throw new Error(sizeError);
    }
  }
  checkTypes(files) {
    const accept = this.options.accept;
    if (accept) {
      let fileType = "";
      if (accept.indexOf("image") !== -1) {
        fileType = "image";
      } else if (accept.indexOf("video") !== -1) {
        fileType = "video";
      }
      for (const file of files) {
        if (file.type.indexOf(fileType) !== 0) {
          return "\u4E0A\u4F20\u6587\u4EF6\u7C7B\u578B\u9519\u8BEF!";
        }
      }
    }
    return null;
  }
  checkSize(files) {
    const options = this.options;
    for (const file of files) {
      if (options.minSize && file.size < options.minSize) {
        return `\u4E0A\u4F20\u6587\u4EF6\u4E0D\u80FD\u5C0F\u4E8E ${options.minSize}`;
      }
      if (options.maxSize && file.size > options.maxSize) {
        return `\u4E0A\u4F20\u6587\u4EF6\u4E0D\u80FD\u5C0F\u4E8E ${options.maxSize}`;
      }
    }
    return null;
  }
  chooseFile() {
    const el = this.el;
    document.body.appendChild(el);
    el.click();
    return new Promise((resolve) => {
      el.onchange = async (e) => {
        let files = e.target.files || [];
        files = Array.prototype.slice.call(files);
        if (files.length === 0) {
          return;
        }
        this.checkFile(files);
        if (this.options.autoUpload) {
          this.uploadFiles(files);
        } else {
          resolve(files);
        }
        el.onchange = null;
        el.parentNode && el.parentNode.removeChild(el);
      };
    });
  }
  on(event, fn) {
    const handler = this.handler[event];
    handler.push(fn);
  }
  off(event, fn) {
    const handles = this.handler[event];
    this.handler[event] = handles.filter((item) => item !== fn);
  }
}
function PromiseEach(promiseLikes) {
  const datas = [];
  let count = 0;
  return new Promise((resolve) => {
    promiseLikes.forEach(async (promiseLike) => {
      try {
        const data2 = await promiseLike;
        datas.push(data2);
      } catch (error) {
        datas.push(error);
      } finally {
        count++;
        if (count === promiseLikes.length) {
          resolve(true);
        }
      }
    });
  });
}
const uploader = new Uploader(async () => "", {
  autoUpload: false,
  limit: 1,
  accept: "*"
});
function useImportTemplate() {
  const importTemplate = async () => {
    const [file] = await uploader.chooseFile();
    const reader = new FileReader();
    return new Promise((resolve, reject) => {
      reader.onload = function(evt) {
        if (!evt.target) {
          reject();
          return;
        }
        try {
          const pageData = MjmlToJson(evt.target.result);
          resolve([file.name, pageData]);
        } catch (error) {
          reject();
        }
      };
      reader.readAsText(file);
    });
  };
  return { importTemplate };
}
function useExportTemplate() {
  const exportTemplate = (name, page) => {
    var data2 = new Blob([page], { type: "text/html" });
    const downloadUrl = window.URL.createObjectURL(data2);
    const a = document.createElement("a");
    const event = new MouseEvent("click");
    a.download = name;
    a.href = downloadUrl;
    a.dispatchEvent(event);
  };
  return { exportTemplate };
}
function copy(text) {
  const input = document.createElement("textarea");
  input.value = text;
  input.style.position = "fixed";
  input.style.left = "-9999px";
  document.body.append(input);
  input.select();
  document.execCommand("Copy");
  document.body.removeChild(input);
}
function NewHeroPanel() {
  console.log('hre');
  const Com = BlockAttributeConfigurationManager.get(BasicType.TEXT);
  return React__default.createElement(Com, null);
}

var CustomBlocksType;
(function(CustomBlocksType2) {
  CustomBlocksType2["Unsubscribe"] = "Unsubscribe";
  CustomBlocksType2["PickyLoop"] = "Loop";
})(CustomBlocksType || (CustomBlocksType = {}));

function Panel() {
  const { focusIdx } = useFocusIdx();
  return  React__default.createElement(AttributesPanelWrapper, null,  React__default.createElement(Collapse, {
    defaultActiveKey: ["1","2"]
  },  React__default.createElement(Collapse.Panel, {
    key: "1",
    header: "Settings"
  },  React__default.createElement(Stack, {
    vertical: true
  },  React__default.createElement(PickyFontSize, {}),
  React__default.createElement(ColorPickerField, {
    label: "Text color",
    name: `${focusIdx}.attributes.color`,
    // inline: true,
    alignment: "center"
  }),  React__default.createElement(ColorPickerField, {
    label: "Background color",
    name: `${focusIdx}.attributes.container-background-color`,
    // inline: true,
    alignment: "center"
  }),
  )),
  React__default.createElement(Collapse.Panel, {
    key: "2",
    header: "Dimensions"
  },  React__default.createElement(Stack, {
    vertical: true
  },
  React__default.createElement(PickyPadding, {}), 
  React__default.createElement(PickyAlign, {}),
  React__default.createElement(PickyFontStyle, {}),
  ))
  ));
}
function loop_panel() {
  const { focusIdx } = useFocusIdx();
  let product_types = [{
    value: 'connector',
    label: 'Connector',
  },
  {
    value: 'chatbot',
    label: 'Chatbots',
  }];
  let attr_url = `${base_url}connector/helpers/attribute/functions.php`;
  const [p_lists, setpl] = C__Users_Picky_Desktop_email_node_modules_react.exports.useState([]);
  const [p_arrays, setpa] = C__Users_Picky_Desktop_email_node_modules_react.exports.useState([]);
  
  C__Users_Picky_Desktop_email_node_modules_react.exports.useEffect(() => {
      const fetch_connectors = async() => {
        let resp = await serverRequest(attr_url,{ flag : btoa('get_all_connectors_json') });
        if(resp && resp.code == 200){
            setpl(resp.response);
        }
      }
      fetch_connectors();
  },[]);

  let res = useField(`${focusIdx}.attributes.list`);
  let conn = res.input.value;
  C__Users_Picky_Desktop_email_node_modules_react.exports.useEffect(() => {
    if(conn){
      let loader = document.querySelector('.picky_loop_loader');
      if(loader){
        loader.classList.add("pickyshow");
        let sib = loader.parentNode.nextElementSibling;
        loader.parentNode.style.display = 'block';
        sib.style.display = 'none';
      }
      const fetch_arrays = async(id) => {
        let resp = await serverRequest(attr_url,{ flag : btoa('get_loop_array_json'),loop_id : id });
        if(resp && resp.code == 200){
            setpa(resp.response);
        }
        if(loader){
            loader.classList.remove("pickyshow");
            let sib = loader.parentNode.nextElementSibling;
            loader.parentNode.style.display = 'none';
            sib.style.display = 'block';
        }
      }
      fetch_arrays(conn);
    }
  },[conn]);
    // let res = useField(`${focusIdx}.attributes.array`);
    // console.log(res.input.value);
    // console.log(focusIdx);
  return  React__default.createElement(AttributesPanelWrapper, null,  React__default.createElement(Collapse, {
    defaultActiveKey: ["1","2","3","4"]
  },  React__default.createElement(Collapse.Panel, {
    key: "1",
    header: "Loop Settings"
  },  React__default.createElement(Stack, {
    vertical: true
  },  React__default.createElement(TextField, {
      label: "Loop Limit",
      name: `${focusIdx}.attributes.limit`,
      quickchange: true,
      type: "number",
      max : 25
    }),
    React__default.createElement(TextField, {
      label: "Loop Variable",
      name: `${focusIdx}.attributes.variable`
    }),
    React__default.createElement(PickyWidth,{ label : 'Width of Loop Block'}),
  )),
  React__default.createElement(Collapse.Panel, {
    key: "4",
    header: "Sample Loop Array"
  },  React__default.createElement(Stack, {
    vertical: true
  },  /*React__default.createElement(SelectField, {
      label: "Type",
      name: `${focusIdx}.attributes.type`,
      options : product_types
    }),*/
    React__default.createElement(SelectField, {
      label: "Select Connector",
      name: `${focusIdx}.attributes.list`,
      options : p_lists
    }),
    React__default.createElement(PickySpin, {
      size : 30,
      className : 'picky_loop_loader picky_loader',
      block : true
    }),
    React__default.createElement(SelectField, {
      label: "Select Loop Array",
      name: `${focusIdx}.attributes.array`,
      options : p_arrays
    }),
  )),
  React__default.createElement(Collapse.Panel, {
    key: "2",
    header: "Container Customization"
  },  React__default.createElement(Stack, {
    vertical: true
  },
  React__default.createElement(ColorPickerField, {
    label: "Background color",
    name: `${focusIdx}.attributes.outer-background`,
    // inline: true,
    alignment: "center"
  }),
  React__default.createElement(PickyUnitField, {
    label: "Border Radius",
    name: `${focusIdx}.attributes.outer-radius`,
    // inline: true,
    alignment: "center"
  }),
  React__default.createElement(TextField, {
    label: "Border",
    name: `${focusIdx}.attributes.outer-border`,
    // inline: true,
    alignment: "center"
  }),
  React__default.createElement(PickyPadding, {
      title : 'Container Padding',
      attributeName : 'outer-padding'
  })
  )),
  React__default.createElement(Collapse.Panel, {
    key: "3",
    header: "Block Customization"
  },  React__default.createElement(Stack, {
    vertical: true
  },
  React__default.createElement(ColorPickerField, {
    label: "Background color",
    name: `${focusIdx}.attributes.background-color`,
    // inline: true,
    alignment: "center"
  }),
  React__default.createElement(PickyUnitField, {
    label: "Border Radius",
    name: `${focusIdx}.attributes.border-radius`,
    // inline: true,
    alignment: "center"
  }),
  React__default.createElement(TextField, {
    label: "Border",
    name: `${focusIdx}.attributes.border`,
    // inline: true,
    alignment: "center"
  }),
  React__default.createElement(PickyPadding,null),
//   React__default.createElement(PickyPadding, {
//       title : 'Margin',
//       attributeName : 'margin'
//   })
  ))
  ));
}
const { Section, Column, Image, Button,Text,Wrapper } = index;

const Unsubscribe = {
  name: "UnSubscribe",
  type: CustomBlocksType.Unsubscribe,
  create(payload) {
    const defaultData = {
      type: CustomBlocksType.Unsubscribe,
      data: {
        value: {
          info: `If you don't wish to receive further emails from our company then you kindly <a href="{{unsubscribe}}">Unsubscribe</a>`,
        }
      },
      attributes: {
          padding : '10px 0px 10px 0px',
          align: 'center',
        //   height: '25px',
          'font-size' : '13px',
          'line-height' : '1.7',
          'letter-spacing' : '0px',
          'font-style': "normal"
      },
      children: []
    };
    return lodash.exports.merge(defaultData, payload);
  },
  validParentType: [BasicType.PAGE, AdvancedType.WRAPPER, BasicType.WRAPPER],
  render(params) {
    let { data, idx, mode, context, dataSource } = params;
    const { info } = data.data.value;
    const attributesUnsubs = data.attributes;
//    console.log(PickygetContentEditableClassName(BasicType.TEXT, `${idx}.data.value.info`));
    // const instance = React__default.createElement(Wrapper, {
    //   "css-class": mode === 'testing' ? getPreviewClassName(idx, data.type) : '',
    //   border: "none",
    //   direction: "ltr",
    // //   "text-align": attributesUnsubs['padding'],
    //   "background-color": attributesUnsubs['container-background-color'],
    //   padding: attributesUnsubs['padding'],
    //   height: attributesUnsubs['height']
    // },
    // React__default.createElement(Text, {
    //   "font-size": attributesUnsubs['font-size'],
    //   'font-style': attributesUnsubs['font-style'], 
    // //   padding: "0px",
    //   "line-height": attributesUnsubs['line-height'],
    //   align: attributesUnsubs['align'],
    //   color: attributesUnsubs['color'],
    //   "css-class": PickygetContentEditableClassName(BasicType.TEXT, `${idx}.data.value.info`).join(' ')
    // }, info));
    const insta = React__default.createElement(Wrapper, {
      width: "100%",
      padding: '0px'
    }, React__default.createElement(Section, {
    //   align: attributesUnsubs['text-align']
        padding: '0px'
    }, React__default.createElement(Column, {
      "css-class": mode === 'testing' ? getPreviewClassName(idx, data.type) : '',
      border: "none",
      direction: "ltr",
    //   "text-align": attributesUnsubs['padding'],
      "background-color": attributesUnsubs['container-background-color'],
      padding: attributesUnsubs['padding'],
      height: attributesUnsubs['height']
    }, React__default.createElement(Text, {
      "font-size": attributesUnsubs['font-size'],
      'font-style': attributesUnsubs['font-style'], 
      padding: "0px",
      "line-height": attributesUnsubs['line-height'],
      align: attributesUnsubs['align'],
      color: attributesUnsubs['color'],
      "css-class": PickygetContentEditableClassName(BasicType.TEXT, `${idx}.data.value.info`).join(' ')
    }, info))));
    return insta;
  }
};

const PickyLoop = {
  name: "Loop",
  type: CustomBlocksType.PickyLoop,
  create(payload) {
    const defaultData = {
      type: CustomBlocksType.PickyLoop,
      data: {
        value: {}
      },
      attributes: {
          "limit": 100,
          "variable":"loop",
          "type":"connector",
          "sample":"860.p",
          "outer-border":"none",
          "outer-radius":"0px",
          "outer-padding":"10px 10px 10px 10px",
          "margin" : "0px 0px 0px 0px",
          padding : '10px 10px 10px 10px',
          border: "none",
          "border-radius":"0px",
          align: 'center',
          width: '100%',
      },
      children: []
    };
    return lodash.exports.merge(defaultData, payload);
  },
  validParentType: [BasicType.PAGE, AdvancedType.WRAPPER, BasicType.WRAPPER],
  render(params) {
    // console.log(params);
    let { data, idx, mode, context, dataSource } = params;
    const { info } = data.data.value;
    const attributesUnsubs = data.attributes;
    let variable = (attributesUnsubs['variable']) ? attributesUnsubs['variable'] : 'loop';
    let direction_params = `loop.${variable}.${attributesUnsubs['limit']}`;
    let sample_array = (attributesUnsubs['array']) ? `pky${attributesUnsubs['array']}` : '';
    
//    console.log(attributesUnsubs);
    
    const instance = React__default.createElement(Section, {
       direction: direction_params,
       padding: attributesUnsubs['outer-padding'],
       width: '100%',
       "text-align": "center",
       "border": attributesUnsubs['outer-border'],
       "border-radius": attributesUnsubs['outer-radius'],
       "background-color": attributesUnsubs['outer-background'],
       "css-class": mode === 'testing' ? getPreviewClassName(idx, data.type) + ` picky_loop_container ${sample_array}` : '',
    },React__default.createElement(PickyBasicBlock, {
      params: params,
      tag: "mj-column"
    }));
    return instance;
  }
};

BlockManager.registerBlocks({ 
    [CustomBlocksType.Unsubscribe]: Unsubscribe,
    [CustomBlocksType.PickyLoop]: PickyLoop
});
BlockAttributeConfigurationManager.add({
//   [AdvancedType.HERO]: NewHeroPanel,
  [CustomBlocksType.Unsubscribe]: Panel,
  [CustomBlocksType.PickyLoop]: loop_panel
});

// console.log(BasicType.PAGE,CustomBlocksType.PickyLoop);
const fontList = [
  "Arial",
  "Tahoma",
  "Verdana",
  "Times New Roman",
  "Courier New",
  "Georgia",
  "Arial Black",
  "Comic Sans MS",
  "Impact",
  "Lucida Sans Unicode",
  "Trebuchet MS",
  "Courier New",
  "Lucida Console",
  "monospace"
].map((item) => ({ value: item, label: item }));
const categories = [
  {
    label: "Content Blocks",
    active: true,
    blocks: [
      {
        type: AdvancedType.TEXT
      },
      {
        type: AdvancedType.IMAGE,
        payload: { attributes: { padding: "0px 0px 0px 0px" } }
      },
      {
        type: AdvancedType.BUTTON
      },
      {
        type: AdvancedType.SOCIAL
      },
      {
        type: AdvancedType.DIVIDER
      },
      {
        type: AdvancedType.SPACER
      },
      {
        type: BasicType.HERO
      },
      {
        type: AdvancedType.WRAPPER
      }
    ]
  },
  {
    label: "Action Blocks",
    active: true,
    displayType: "custom",
    blocks: [
      React__default.createElement(PickyBlockAvatarWrapper, {
          type: CustomBlocksType.Unsubscribe
        }, React__default.createElement("div", {
          className: "_blockItem_1ajtj_6"
        }, React__default.createElement("div", {
          className: "_blockItemContainer_1ajtj_16"
        }, React__default.createElement("svg", {
          width: "22px",
          height: "22px",
          xmlns: "http://www.w3.org/2000/svg",
          viewBox: "0 0 640 512"
        },React__default.createElement("path", {
          d: "M256 512c141.4 0 256-114.6 256-256S397.4 0 256 0S0 114.6 0 256S114.6 512 256 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"
        })), React__default.createElement("span", {
          className: "arco-typography",
          style: {
            marginTop: '10px',
            fontSize : '90%'
          }
        }, "Unsubscribe")))),
      React__default.createElement(PickyBlockAvatarWrapper, {
          type: CustomBlocksType.PickyLoop
        }, React__default.createElement("div", {
          className: "_blockItem_1ajtj_6",
          style:{ margin: '0px' }
        }, React__default.createElement("div", {
          className: "_blockItemContainer_1ajtj_16"
        }, React__default.createElement("svg", {
          width: "22px",
          height: "22px",
          xmlns: "http://www.w3.org/2000/svg",
          viewBox: "0 0 640 512"
        },React__default.createElement("path", {
          d: "M0 241.1C0 161 65 96 145.1 96c38.5 0 75.4 15.3 102.6 42.5L320 210.7l72.2-72.2C419.5 111.3 456.4 96 494.9 96C575 96 640 161 640 241.1v29.7C640 351 575 416 494.9 416c-38.5 0-75.4-15.3-102.6-42.5L320 301.3l-72.2 72.2C220.5 400.7 183.6 416 145.1 416C65 416 0 351 0 270.9V241.1zM274.7 256l-72.2-72.2c-15.2-15.2-35.9-23.8-57.4-23.8C100.3 160 64 196.3 64 241.1v29.7c0 44.8 36.3 81.1 81.1 81.1c21.5 0 42.2-8.5 57.4-23.8L274.7 256zm90.5 0l72.2 72.2c15.2 15.2 35.9 23.8 57.4 23.8c44.8 0 81.1-36.3 81.1-81.1V241.1c0-44.8-36.3-81.1-81.1-81.1c-21.5 0-42.2 8.5-57.4 23.8L365.3 256z"
        })), React__default.createElement("span", {
          className: "arco-typography",
          style: {
            marginTop: '10px',
            fontSize : '90%'
          }
        }, "Loop"))))
    ]
  },
  {
    label: "Layout",
    active: true,
    displayType: "column",
    blocks: [
      {
        title: "2 columns",
        payload: [
          ["50%", "50%"],
          ["33%", "67%"],
          ["67%", "33%"],
          ["25%", "75%"],
          ["75%", "25%"]
        ]
      },
      {
        title: "3 columns",
        payload: [
          ["33.33%", "33.33%", "33.33%"],
          ["25%", "25%", "50%"],
          ["50%", "25%", "25%"]
        ]
      },
      {
        title: "4 columns",
        payload: [["25%", "25%", "25%", "25%"]]
      }
    ]
  }
];
BlockManager.getBlockByType(BasicType.PAGE);

const beautifyMJML = (str) => {
    let d = new DOMParser().parseFromString(str, "text/html");
    // Looping through sections
    let sec = d.getElementsByTagName("mj-section");
    for (let i = 0; i < sec.length; i++) {
        let flg = sec[i].getAttribute('direction');
        if(flg && flg.startsWith('loop')){
            sec[i].setAttribute("css-class", "picky_loop_node");
        }
    }
    let ml = d.getElementsByTagName('body')[0].innerHTML;
    return ml;
}
const onUploadImage = async (blob) => {
    console.log(blob.size);
    return false;
    var mb = blob.size / 1024 / 1024;
    if(mb > 2){
        message.error('Please update image in less than 2 MB');
        return null;
    }
    let url = `${base_url}builder/builder.php?action=upload_file`;
    let resp = await serverRequest(url,{ pickyfile : blob });
    if(resp.code == 200 && resp.url){
        return resp.url;
    }else{
        message.error('Failed to upload the image, please try agin!');
        return null;
    }
};


var email_editor = {};

email_editor.currentSelection;
email_editor.caret;
email_editor.active;
email_editor.selection;
email_editor.root;

email_editor.backuproot = (el,wdw=false) => {
    
    // console.log(el.shadowRoot);
    
  let shadowRootSelection = (wdw) ? window.getSelection() : el.shadowRoot.getSelection();
  
    email_editor.selection = document.createRange();
    email_editor.selection.setStart(shadowRootSelection.anchorNode, shadowRootSelection.anchorOffset);
    email_editor.selection.setEnd(shadowRootSelection.focusNode, shadowRootSelection.focusOffset);
    
    var range = shadowRootSelection.getRangeAt(0);
    email_editor.currentSelection = {"startContainer": range.startContainer, "startOffset":range.startOffset,"endContainer":range.endContainer, "endOffset":range.endOffset};
    // if(shadowRootSelection.anchorNode.parentNode.tagName == 'A'){
    
    // console.log(range.startContainer.parentNode);
    
    // console.log(range.startContainer.parentNode,range.startContainer.parentNode.contentEditable);
    if(range.startContainer.parentNode.contentEditable !== 'true'){
        if(range.startContainer.parentNode.closest('[contenteditable]')){
            email_editor.focus = [...range.startContainer.parentNode.closest('[contenteditable]').childNodes].indexOf(range.startContainer.parentNode);
        }
    }else{
        email_editor.focus = undefined;
    }
        
    let parent = range.startContainer.parentNode;
    if(parent.contentEditable){
        parent = parent.closest('[contenteditable]');
    }
    if(parent && parent.closest('.picky_loop_container')){
        let idx = parent.dataset.content_editableIdx;
        idx = idx.replace('.data.value.content','');
        window.attrs[idx] = parent.innerHTML;
    }
    // }
}

email_editor.restoreroot = (el,text)=>{
    // console.log(email_editor.selection.startContainer);
    // console.log(email_editor.selection.startContainer.querySelectorAll('[contenteditable=true]')[0].childNodes);
    if(email_editor.selection.startContainer.tagName == 'TD'){
      email_editor.currentSelection.startContainer = email_editor.currentSelection.endContainer =
      (typeof email_editor.focus === "undefined") ? email_editor.selection.startContainer.querySelectorAll('[contenteditable=true]')[0].childNodes[0] :
      email_editor.selection.startContainer.querySelector('[contenteditable=true]').childNodes[email_editor.focus].childNodes[0];
    }
    let firefox = (navigator.userAgent.indexOf("Firefox") > 0) ? true : false;
  var selection = (firefox) ? window.getSelection() : el.shadowRoot.getSelection();
    selection.removeAllRanges();
    var range = document.createRange();
    range.setStart(email_editor.currentSelection.startContainer, email_editor.currentSelection.startOffset);
    range.setEnd(email_editor.currentSelection.endContainer, email_editor.currentSelection.endOffset);
    selection.addRange(range);
}

email_editor.restoreinput = () =>{ 
  let inp = email_editor.active;
  let caretPos = email_editor.caret;
  if(inp != null) {
        if(inp.createTextRange) {
            var range = inp.createTextRange();
            range.move('character', caretPos);
            range.select();
        }
        else {
            if(inp.selectionStart) {
                inp.focus();
                inp.setSelectionRange(caretPos, caretPos);
            }
            else
                inp.focus();
        }
    }
}

function handleEdit(e) {
  let el = e.target;
//   return false;
  if(el.closest('#easy-email-editor')){
    // Shitheesh
    let firefox = (navigator.userAgent.indexOf("Firefox") > 0) ? true : false;
    let selection = (firefox) ? window.getSelection() : el.shadowRoot.getSelection();
    if(selection.anchorNode){
      email_editor.backuproot(el,firefox);
      email_editor.root = el;
      email_editor.caret = undefined;
      let act = e.composedPath()[0];
      email_editor.active = act;
    }
    if(!firefox){
        // let selection = el.shadowRoot.getSelection();
        // if(selection.anchorNode){
        //   email_editor.backuproot(el);
        //   email_editor.root = el;
        //   email_editor.caret = undefined;
        //   let act = e.composedPath()[0];
        //   email_editor.active = act;
        // }
    }else{
        
        // email_editor.backuproot(el,true);
    //  let shadowRootSelection = window.getSelection();
    //  console.log(shadowRootSelection);
      
        // email_editor.selection = document.createRange();
        // email_editor.selection.setStart(shadowRootSelection.anchorNode, shadowRootSelection.anchorOffset);
        // email_editor.selection.setEnd(shadowRootSelection.focusNode, shadowRootSelection.focusOffset);
        
        // var range = window.getSelection().getRangeAt(0);
        // console.log(range);
        // var preCaretRange = range.cloneRange();
        // preCaretRange.selectNodeContents(div);
        // preCaretRange.setEnd(range.endContainer, range.endOffset);
        // var cursorPos = preCaretRange.toString().length;
    }
  }else if(el.classList.contains('arco-input')){
    email_editor.caret = el.selectionStart;
    email_editor.active = el;
  }
}

document.addEventListener('keyup',handleEdit,false);
document.addEventListener('click',handleEdit,false);

var Jsontree = {};
// container,json,Name,Prefix for JSON
Jsontree.create = (c, j,n,p) => {
    let h = (!Jsontree.empty(j)) ? `
            <ul class="JSONtree treeCss tree">
                <li class="branch">
                    <div class="treehead parenthead">
                        ${Jsontree.expander('close')}
                        <a class="treeHrefCss" href="#">${n}</a>
                    </div>
                    <ul>${Jsontree.generate(j,p)}</ul>
                </li>
            </ul>` : 
            `<div class="empty-tree">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512c141.4 0 256-114.6 256-256S397.4 0 256 0S0 114.6 0 256S114.6 512 256 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-144c-17.7 0-32-14.3-32-32s14.3-32 32-32s32 14.3 32 32s-14.3 32-32 32z"/></svg>
                <div class="info-tree">Looks like there is no array found in this loop block, please select a valid sample array in the loop settings.</div>
            </div>`;
    // let tree = new DOMParser().parseFromString(h, "text/html");
    // console.log(tree);
    c.innerHTML = h;
    if(!Jsontree.empty(j)){
        for (let attrs of c.querySelectorAll(".atrs")) {
            // attrs.addEventListener("click", (e) => {
            //   let key = e.target.dataset.key;
            //   // setatrpop(true);
            //   if(key) insert_attr(key);
            // });
        }
        for (let item of c.querySelectorAll(".treehead")) {
            item.addEventListener("click", (e) => {
                let br = e.target.closest('.branch');
                // Determine whether show or Hide
                let plus = br.querySelector('svg.open-icon');
                let type = (Jsontree.hidden(plus)) ? 'close' : 'open';
                let children = br.querySelector('ul').children;
                for (let child of children) {
                    child.style.display = (type == 'open') ? 'block' : 'none';
                }
                // console.log(e);
                
                if(type == 'open'){
                    // Closing the particular Branch
                    br.querySelector('svg.open-icon').style.display = "none";
                    br.querySelector('svg.close-icon').style.display = "block";
                }else{
                    // Opening the branch
                    br.querySelector('svg.open-icon').style.display = "block";
                    br.querySelector('svg.close-icon').style.display = "none";

                }
            });
        }
        c.querySelector('.parenthead').click();
    }
    
};

Jsontree.empty = (value) => {
    return (
        (value == null) ||
        (value.hasOwnProperty('length') && value.length === 0) ||
        (value.constructor === Object && Object.keys(value).length === 0)
    );
}

Jsontree.search = (cont,key) => {
    if (key) {
        key = key.toLowerCase();
        let li = cont.querySelectorAll('li');
        for (let list of li) {
            if(!list.classList.contains('branch')){
                let value = list.innerText.trim().toLowerCase();
                if (value.includes(key)) {
                    Jsontree.display(list,'show');
                } else {
                    Jsontree.display(list,'hide');
                }
            }else{
                Jsontree.display(list,'hide');
                let current_parent = list;
                let sublist = list.querySelectorAll('li');
                for (let innerlist of sublist) {
                    if(innerlist.classList.contains('branch')){
                        let value = innerlist.innerText.trim().toLowerCase();
                        if (value.includes(key)) {
                            Jsontree.display(innerlist.querySelector('.open-icon'),'hide');
                            Jsontree.display(innerlist.querySelector('.close-icon'),'show');
                            Jsontree.display(current_parent,'show');
                        }
                    }else{
                        let value = innerlist.innerText.trim().toLowerCase();
                        if (value.includes(key)) {
                            Jsontree.display(current_parent,'show');
                        }
                    }
                }
            }
        }
    } else {
        let li = cont.querySelectorAll('li');
        for (let list of li) {
            Jsontree.display(list,'show');
            let head = list.querySelectorAll('.treehead');
            for (let h of head) {
                Jsontree.display(h.querySelector('.open-icon'),'hide');
                Jsontree.display(h.querySelector('.close-icon'),'show');
            }
        }
    }
}

Jsontree.display = (el,type) => {
    el.style.display = (type == 'show') ? 'block' : 'none';
}

Jsontree.hidden = (el) => {
    var style = window.getComputedStyle(el);
    return (style.display === 'none');
}

Jsontree.generate = (j,p) => {
    let html = '';

    for (const [key, value] of Object.entries(j)) {
        let k = (p || p===0) ? `${p}.${key}` : key;
        if(Array.isArray(value) || typeof value === "object"){
            html += `<li class="branch" style="display:none;">
                        <div class="treehead">
                            ${Jsontree.expander('close')}
                            <span class="atrs">${key}</span>
                        </div>
                        <ul>${Jsontree.generate(value,k)}</ul>
                    </li>`
        }else{
            html += `<li style="display:none;">
                        <span class="atrs" data-key="${k}">${key}</span>
                        <span><i class="treeEmptyCss">${value}</i></span>
                    </li>`;
        }
    }

    // console.log(html);
    return html;
}

Jsontree.expander = (t='open') => {
    return `
        <svg class="close-icon" style="${(t=='open') ? '' : 'display:none;'}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512c141.4 0 256-114.6 256-256S397.4 0 256 0S0 114.6 0 256S114.6 512 256 512zM184 232H328c13.3 0 24 10.7 24 24s-10.7 24-24 24H184c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/></svg>
        <svg class="open-icon" style="${(t=='close') ? '' : 'display:none;'}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512c141.4 0 256-114.6 256-256S397.4 0 256 0S0 114.6 0 256S114.6 512 256 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/></svg>`;
}

function Editor() {
  const [downloadFileName, setDownloadName] = C__Users_Picky_Desktop_email_node_modules_react.exports.useState("download.mjml");
  const [template, setTemplate] = C__Users_Picky_Desktop_email_node_modules_react.exports.useState(templateData);
  const [tmpname, setName] = C__Users_Picky_Desktop_email_node_modules_react.exports.useState("Template New");
  const template_id = document.getElementById('root').dataset.id;
  const [perpop, setperpop] = C__Users_Picky_Desktop_email_node_modules_react.exports.useState(false);
  const [atrpop, setatrpop] = C__Users_Picky_Desktop_email_node_modules_react.exports.useState(false);
  const [searchkey, setatrkey] = C__Users_Picky_Desktop_email_node_modules_react.exports.useState("");
  const [loaded, setLoaded] = C__Users_Picky_Desktop_email_node_modules_react.exports.useState(false);
  
  // Fetching the Template details from the server, if its edit mode
  C__Users_Picky_Desktop_email_node_modules_react.exports.useEffect(() => {
    const gettemplatedata = async (id,action='fetch_template') => {
            let url = `${base_url}builder/builder.php?id=${id}&action=${action}`;
            let resp = await serverRequest(url);
            setLoaded(true);
            if(resp.code == 200 && resp.data && resp.data.json){
                let temp_json = JSON.parse(resp.data.json);
                // console.log(temp_json);
                setTemplate(temp_json.content);
                setName(resp.data.template_name);
            }
    };
    let urlSearchParams = new URLSearchParams(window.location.search);
        let params = Object.fromEntries(urlSearchParams.entries());
        let import_id = parseInt(params.import);
    if(template_id){
        gettemplatedata(template_id);
    }else if(parseInt(import_id)){
        gettemplatedata(import_id,'import_template');
    }else{
        setName('Template New');
            setLoaded(true);
    }
  }, []);
//   console.log(template);
  const { importTemplate } = useImportTemplate();
  const { exportTemplate } = useExportTemplate();
  const { width } = useWindowSize();
  const smallScene = width < 900;
  const showAttribute = (e) => {
    setatrkey('');
    
    // if(variable){
        setTimeout(async() => {
          let modal_loader = document.querySelector('.picky_attr_loader');
          let modal_cont = document.querySelector('.picky_attr_container');
          modal_loader.classList.add('pickyshow');
          modal_cont.style.display = 'none';
          // Things After loading the JSOn data
          let variable = (e.target.tagName != 'path') ? e.target.dataset.array : e.target.closest('.show_picky_attrs').dataset.array;
        //   console.log(e.target.tagName,e.target.closest('.show_picky_attrs').dataset.array);
          let resp = await serverRequest(
            `${base_url}connector/helpers/attribute/functions.php`,
            { flag : btoa('get_loop_attribute_json'),loop_id : variable }
          );
          let json = (resp.code == 200) ? resp.response : {};
          modal_loader.classList.remove('pickyshow');
          modal_cont.style.display = 'block';
          let container = modal_cont.querySelector('.json_tree_container');
          if(Jsontree.empty(json)){
            modal_cont.querySelector('.tree_search_container').style.display = 'none';
          }else{
            modal_cont.querySelector('.tree_search_container').style.display = 'block';
          }
          Jsontree.create(container,json,'Loop Array','');
          modal_cont.querySelector('.picky_attr_search').focus();
        }, 100);
    // }
    setatrpop(true);
  }
  const searchtree = (str) =>{ 
    setatrkey(str);
    let cont = document.querySelector('.json_tree_container');
    Jsontree.search(cont,str);
  }
  const insertTextAtCursor = (text) =>{
      let selection = document.getSelection();
    //   console.log(selection)
    //   let range = selection.getRangeAt(0);
    //   range.deleteContents();
    //   let node = document.createTextNode(text);
    //   range.insertNode(node);
    
    //     for(let position = 0; position != text.length; position++)
    //     {
    //         selection.modify("move", "right", "character");
    //     };
   }
  const insertAttr = (el) => {
    // 
    if(el.classList.contains('atrs')){
      setatrpop(false);
      let key = el.dataset.key;
      setTimeout(() => {
        // console.log(email_editor.caret);
        if(email_editor && (email_editor.caret || email_editor.caret == 0 )){
          email_editor.restoreinput();
          document.execCommand("insertHTML", false, `{{${key}}}`);
        }else if(email_editor){
          email_editor.restoreroot(email_editor.root,`{{${key}}}`);
        //   return false;
          document.execCommand("insertHTML", false, `{{${key}}}`);
          let firefox = (navigator.userAgent.indexOf("Firefox") > 0) ? true : false;
          email_editor.backuproot(email_editor.root.closest('#VisualEditorEditMode'),firefox);
          
          let btn = document.querySelector(".show_picky_attrs");
          let idx = btn.dataset.idx;
          var evt = new MouseEvent("click", {
            view: window,
            bubbles: true,
            cancelable: true,
            clientX: 20
           });
           email_editor.currentSelection.startContainer.dispatchEvent(evt);
        }
      },100);
    }
  }
  const onCopyHtml = (values) => {
    const html = mjml(JsonToMjml({
      data: values.content,
      mode: "production",
      context: values.content
    }), {
      beautify: true,
      validationLevel: "soft"
    }).html;
    copy(html);
    message.success("Copied to pasteboard!");
  };
  const onImportMjml = async () => {
    try {
      const [filename, data2] = await importTemplate();
      setDownloadName(filename);
      setTemplate(data2);
    } catch (error) {
      message.error("Invalid mjml file");
    }
  };
  const onExportMjml = (values) => {
    exportTemplate(downloadFileName, JsonToMjml({
      data: values.content,
      mode: "production",
      context: values.content
    }));
  };
  const changetemplatename = (str) =>{ setName(str);}
  const onSubmit = C__Users_Picky_Desktop_email_node_modules_react.exports.useCallback(async (values, form) => {
        let Rootel = document.getElementById('root');
        if(Rootel.dataset.saving && (Rootel.dataset.saving != 'null')) return false;
        Rootel.dataset.saving = true;
        let beauty = beautifyMJML(JsonToMjml({
          data: values.content,
          mode: "production",
          context: values.content
        }));
        const html = mjml(beauty, {
          beautify: true,
          validationLevel: "soft"
        }).html;
        // const html = mjml(JsonToMjml({
        //   data: values.content,
        //   mode: "production",
        //   context: values.content
        // }), {
        //   beautify: true,
        //   validationLevel: "soft"
        // }).html;
        // return false;
        // console.log(values);
        let id = Rootel.dataset.id;
        let data = {
            action : 'save_template',
            json : JSON.stringify(values),
            html,
            name : document.querySelector('[name="pky_template_name"]').value
        }
        let url = `${base_url}builder/builder.php?id=${(id) ? id : ''}`;
        let resp = await serverRequest(url,data);
        Rootel.dataset.saving = null;
        if(resp.code == 200){
            message.success('Tempate data saved successfully !');
            if(resp.template_id){
               document.getElementById('root').dataset.id = resp.template_id;
               window.history.replaceState({}, null, `${base_url}templates/build/${resp.template_id}`);
            }
        }else{
            message.error('Failed to save template data, please try again!');
        }
        
  }, []);
  const initialValues = C__Users_Picky_Desktop_email_node_modules_react.exports.useMemo(() => {
    return {
      subject: "Welcome to Picky Assist",
      subTitle: "Nice to meet you!",
      content: template
    };
  }, [template]);
  if (!initialValues)
    return null;
  return  React__default.createElement("div", null,  React__default.createElement(EmailEditorProvider, {
    dashed: false,
    data: initialValues,
    height: "calc(100vh - 85px)",
    onUploadImage:onUploadImage,
    // mergeTags: pky_attributes,
    autoComplete: true,
    fontList,
    onSubmit
  }, ({ values }, { submit }) => {
      return (!loaded) ? React__default.createElement("div",{
          className : 'picky_main_loader'
      },
          React__default.createElement(PickySpin, {
              size : 50,
              className : 'picky_loop_loader picky_loader pickyshow',
              block : true 
          })
        ) :  React__default.createElement(React__default.Fragment, null,  React__default.createElement(PageHeader, {
      title: "",
      extra:  React__default.createElement(Stack, {
        alignment: "center"
      },
      React__default.createElement("input", {
        name: "pky_template_name",
        value : tmpname,
        onChange : (e) =>{ changetemplatename(e.target.value); }
      }),
      React__default.createElement("div", {
          className: "picky_style_button show_picky_attrs",
          style: { display: 'none'},
          onClick: (e) => showAttribute(e)
        }, React__default.createElement("svg", {
          width: "18px",
          height: "18px",
          xmlns: "http://www.w3.org/2000/svg",
          viewBox: "0 0 640 512"
        },React__default.createElement("path", {
          d: "M392.8 1.2c-17-4.9-34.7 5-39.6 22l-128 448c-4.9 17 5 34.7 22 39.6s34.7-5 39.6-22l128-448c4.9-17-5-34.7-22-39.6zm80.6 120.1c-12.5 12.5-12.5 32.8 0 45.3L562.7 256l-89.4 89.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l112-112c12.5-12.5 12.5-32.8 0-45.3l-112-112c-12.5-12.5-32.8-12.5-45.3 0zm-306.7 0c-12.5-12.5-32.8-12.5-45.3 0l-112 112c-12.5 12.5-12.5 32.8 0 45.3l112 112c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256l89.4-89.4c12.5-12.5 12.5-32.8 0-45.3z"
        }))),
        React__default.createElement("div", {
          className: "picky_icon_button"
        }, React__default.createElement("svg", {
          width: "26px",
          height: "26px",
          onClick: () => setperpop(!perpop),
          xmlns: "http://www.w3.org/2000/svg",
          viewBox: "0 0 512 512"
        },React__default.createElement("path", {
            d: "M256 512c141.4 0 256-114.6 256-256S397.4 0 256 0S0 114.6 0 256S114.6 512 256 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-144c-17.7 0-32-14.3-32-32s14.3-32 32-32s32 14.3 32 32s-14.3 32-32 32z"
        }))),
      React__default.createElement("button", {
        className : 'arco-btn arco-btn-primary arco-btn-size-default arco-btn-shape-square',
        onClick: () => submit()
      }, "Save"),
      )
    }),  React__default.createElement(StandardLayout, {
      compact: !smallScene,
      categories,
      showSourceCode: true
    },  React__default.createElement(EmailEditor, null)));
  }),
  React__default.createElement(PickyModal, {
      title: "Email Personalization",
      alignCenter: false,
      style: {
        top: 50
      },
      visible: perpop,
      footer: null,
      onCancel: () => {
        setperpop(!perpop);
      }
    }, 
    React__default.createElement("p", null, React__default.createElement("b", null, "Personalization")), 
    React__default.createElement("p", null, "You can personalize the email by adding {{any_value}} ", null),
    React__default.createElement("p", null, React__default.createElement("b", null, "Example")), 
    React__default.createElement("p", null, "Dear ", React__default.createElement("span", {className: "arco-typography-primary"}, "{{name}}"), " Your billing amount due is ", React__default.createElement("span", {className: "arco-typography-primary"}, "{{amount_due}}"), " and here is the payment link ", React__default.createElement("span", {className: "arco-typography-primary"}, "{{payment_link}}")),
    React__default.createElement("p", null, "After saving the template you will get the option to map these values with relevant attributes and the system will automatically replace these tags with relevant values.")
  ),
   React__default.createElement(PickyModal, {
      title: "Loop Attributes",
      alignCenter: false,
      style: {
        top: 50
      },
      visible: atrpop,
      footer: null,
      onCancel: () => {
        setatrpop(!atrpop);
      }
    },
    React__default.createElement(PickySpin, {
      size : 30,
      className : 'picky_attr_loader picky_loader',
      block : true
    }), 
    React__default.createElement("div", {
      className: "picky_attr_container",
      style : {
        display : 'none'
      }
    }, React__default.createElement("div", {
      className: "tree_search_container"
    }, React__default.createElement("input", {
      type: "text",
      value : searchkey,
      placeholder: 'Search Attributes',
      onChange : (e) =>{ searchtree(e.target.value); },
      className: "picky_attr_search"
    }),
    React__default.createElement("svg", {
      width: "16px",
      height: "16px",
      onClick: () => searchtree(''),
      xmlns: "http://www.w3.org/2000/svg",
      viewBox: "0 0 512 512",
      className : 'close_search_tree',
      style : {
        display : (searchkey) ? 'block' : 'none'
      }
    },React__default.createElement("path", {
      d: "M256 512c141.4 0 256-114.6 256-256S397.4 0 256 0S0 114.6 0 256S114.6 512 256 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"
    }))), React__default.createElement("div", {
      className: "json_tree_container custom_scrollbar_element scrolling",
      onClick : (e) =>{ insertAttr(e.target); }
    }))
    )
);
}
ReactDOM.render( React__default.createElement(Editor, null), document.getElementById("root"));