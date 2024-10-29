var express = require('express');
var cors = require('cors');
var path = require('path');
const mqtt = require('mqtt')
var mongoose = require('mongoose')
var moment = require('moment');
//const ip = '3.108.245.156';
const ip= '52.2.47.212';


const fs = require('fs');
//const mqtt = require('mqtt')
 
const ca = fs.readFileSync('./mosquitto.org.crt').toString()


//const ip = 'ecm.co.in';
const port = 1883;      //9001;
const apiKey = '1b766690-cacf-4953-be64-be34d4175582';

var cookieParser = require('cookie-parser');
//var bodyParser = require('body-parser');
var index = require('./routes/index');
var app = express();
const http = require('http').Server(app);

//var mongoDB = 'mongodb+srv://ecm:kYYVp8R1M5bUG5l7@cluster0.g6qroat.mongodb.net/ecm';
// change the new database string
var mongoDB= 'mongodb+srv://CatAdmin:g6rtwW8IHkNwXXVp@caterpillarcluster.55yil.mongodb.net/?tls=true&connectTimeoutMS=30000/';
mongoose.connect(mongoDB);
const DataModel = require('./models/data');
const InverterModel = require('./models/inverter');
const UserModel = require('./models/user');
const InverterSettingsModel = require('./models/inverter_setting');
const DeviceWarningModel = require('./models/device_warning');
const DeviceNotifModel = require('./models/device_notification');
const DailyActivityModel = require('./models/daily_activity');

app.set('view engine', 'jade');

app.use(cors());
//app.use(logger('dev'));
app.use(express.json());
app.use(express.urlencoded({
  extended: true
}));

app.use(cookieParser());
app.use(express.static(path.join(__dirname, 'public')));

var router = express.Router();
var options1 = {
  port: port,
  host: 'http://'+ip,
  username: 'ecmmqqt',
  password: 'AsmEcmssdqhR'
};
//const client  = mqtt.connect('mqtt://test.mosquitto.org');
 //let client  = mqtt.connect('ws://'+ip+':'+port,options);

  //let client = mqtt.connect(options1.host, { username: options1.username, password: options1.password });
  const client = mqtt.connect('mqtts://test.mosquitto.org:8883', { ca });
client.on('connect', function () {
    console.log('mqtt connected');
    client.subscribe('presence')
    client.publish('presence', 'Hello mqtt')
  

    client.subscribe('read_data/+', function (err) {
    if (!err) {
      //client.publish('read_data', 'Hello mqtt Self Test');
    }

    if (err) {
      console.log('ERROR read_data =>', err);
    }
  });

  var msgData = 'Hello, this is data to write';
  client.publish('write_data', msgData, function (err) {
    if (err) {
      console.log('ERROR write_data =>', err);
    }
  });


});

client.on('message', function (topic, message) {
     console.log('message',message.toString());
  // message is Buffer
 // console.log('message => ', message.toString());
  console.log('topic => ', topic);
  //client.end();
  
  // clear table
  /*DataModel.deleteMany({ status: 1 }).then(function(){
    console.log("Data deleted"); // Success
  }).catch(function(error){
      console.log(error); // Failure
  });*/

  if(topic){
    var msgData =  message.toString();
    const dataMdl = new DataModel;
    var json;
    try {
      json = JSON.parse(msgData);
      if (typeof json === 'object'){
        dataMdl.data = json;
      }
    } catch (e) {
      dataMdl.data = msgData;
    }
     
    dataMdl.topic = topic;
    dataMdl.macid =json.MacId;  // '24:dc:c3:a4:11:14';   //;
    dataMdl.status = 1;
    if(json.data && json.data.DateTime){
        var dateTime = json.data.DateTime;
        dataMdl.created_at_timestamp = moment(dateTime).valueOf();
        dataMdl.created_at = moment(dateTime).format('YYYY-MM-DD HH:mm:ss');
        dataMdl.updated_at = moment(dateTime).format('YYYY-MM-DD HH:mm:ss');
    } else {
        dataMdl.created_at_timestamp = moment().valueOf();
        dataMdl.created_at = moment().format('YYYY-MM-DD HH:mm:ss');
        dataMdl.updated_at = moment().format('YYYY-MM-DD HH:mm:ss');
    }
    if(dataMdl.data){
      //dataMdl.save();
    }

    var min = 0;
    //console.log('json ==> ', json);
    InverterModel.findOne({macid:json.MacId}, {}, { sort: { 'created_at' : -1 } }, function(errInv, invInfo) {
      if(invInfo && json && dataMdl.data){

          // auto verify device if not verified yet and get data
          if( (!invInfo.is_verified || invInfo.is_verified != 1) && json.data.Contain == 'System_calculated'){
            invInfo.is_verified = 1;
            invInfo.verified = "DEVICE_VARIFIED";
            invInfo.save();
            console.log('Verify By Data ==>');
          } 

          dataMdl.company_id = invInfo.company_id;
          if(json.data.Contain == 'Warning' || json.data.Contain == 'Notification'){
            

            if(json.data.Contain == 'Warning'){
              for (const [key, value] of Object.entries(json.data)) {
                console.log(`${key}: ${value}`);
                if(key != 'Contain'){
                  DeviceWarningModel.findOne({macid:json.MacId, code:key, code_date:value}, {}, { sort: { 'created_at_timestamp' : -1 } }, function(err, lastDeviceWarn) {
                    if(lastDeviceWarn){
                      if(json.data && json.data.DateTime){
                        var dateTime = json.data.DateTime;
                        lastDeviceWarn.updated_at = moment(dateTime).format('YYYY-MM-DD HH:mm:ss');
                      } else {
                        lastDeviceWarn.updated_at = moment().format('YYYY-MM-DD HH:mm:ss');
                      }
                      lastDeviceWarn.save();
                    } else {
                      const deviceWarning = new DeviceWarningModel;
                      deviceWarning.macid = json.MacId;
                      deviceWarning.code = key;
                      deviceWarning.code_date = value;
                      deviceWarning.company_id = invInfo.company_id;
                      if(json.data && json.data.DateTime){
                        var dateTime = json.data.DateTime;
                        deviceWarning.created_at_timestamp = moment(dateTime).valueOf();
                        deviceWarning.created_at = moment(dateTime).format('YYYY-MM-DD HH:mm:ss');
                        deviceWarning.updated_at = moment(dateTime).format('YYYY-MM-DD HH:mm:ss');
                      } else {
                        deviceWarning.created_at_timestamp = moment().valueOf();
                        deviceWarning.created_at = moment().format('YYYY-MM-DD HH:mm:ss');
                        deviceWarning.updated_at = moment().format('YYYY-MM-DD HH:mm:ss');
                      }
                      deviceWarning.save();
                    }
                    
                  });
                }
              }
            }

            if(json.data.Contain == 'Notification'){
              for (const [key, value] of Object.entries(json.data)) {
                console.log(`${key}: ${value}`);
                if(key != 'Contain'){
                  DeviceNotifModel.findOne({macid:json.MacId, code:key, code_date:value}, {}, { sort: { 'created_at_timestamp' : -1 } }, function(err, lastDeviceNotif) {
                    if(lastDeviceNotif){
                      if(json.data && json.data.DateTime){
                        var dateTime = json.data.DateTime;
                        lastDeviceNotif.updated_at = moment(dateTime).format('YYYY-MM-DD HH:mm:ss');
                      } else {
                        lastDeviceNotif.updated_at = moment().format('YYYY-MM-DD HH:mm:ss');
                      }

                      lastDeviceNotif.save();
                    } else {
                      const deviceNotif = new DeviceNotifModel;
                      deviceNotif.macid = json.MacId;
                      deviceNotif.code = key;
                      deviceNotif.code_date = value;
                      deviceNotif.company_id = invInfo.company_id;
                      if(json.data && json.data.DateTime){
                        var dateTime = json.data.DateTime;
                        deviceNotif.created_at_timestamp = moment(dateTime).valueOf();
                        deviceNotif.created_at = moment(dateTime).format('YYYY-MM-DD HH:mm:ss');
                        deviceNotif.updated_at = moment(dateTime).format('YYYY-MM-DD HH:mm:ss');
                      } else {
                        deviceNotif.created_at_timestamp = moment().valueOf();
                        deviceNotif.created_at = moment().format('YYYY-MM-DD HH:mm:ss');
                        deviceNotif.updated_at = moment().format('YYYY-MM-DD HH:mm:ss');
                      }
                      deviceNotif.save();
                    }
                    
                  });
                }
              }
            }
            

            json.data.totalcount = Object.keys(json.data).length;
          }

          // update status field for activity
          if(json.data.Contain == 'Battery'){
            var P_DC = json.data.Power['P_DC(W)'];
            console.log('P_DC', P_DC);
            var deviceStatus = '';
            if(P_DC >= 1000){
              deviceStatus = 'discharging';
            } else if(P_DC <= -1000){
              deviceStatus = 'charging';
            } else if(P_DC < 1){
              deviceStatus = 'off';
            } else {
              deviceStatus = "idle";
            }
            console.log('deviceStatus ===> ', deviceStatus);

            DailyActivityModel.findOne({macid:json.MacId}, {}, { sort: { 'created_at_timestamp' : -1 } }, function(err, lastDatasAct) {
              if( (!lastDatasAct) || (lastDatasAct.status != deviceStatus)){
                const dailyActvtyMdl = new DailyActivityModel;
                dailyActvtyMdl.macid = json.MacId;
                dailyActvtyMdl.status = deviceStatus;
                dailyActvtyMdl.is_device_status_update = 1;
                dailyActvtyMdl.company_id = invInfo.company_id;
                if(json.data && json.data.DateTime){
                    var dateTime = json.data.DateTime;
                    dailyActvtyMdl.created_at_timestamp = moment(dateTime).valueOf();
                    dailyActvtyMdl.created_at = moment(dateTime).format('YYYY-MM-DD HH:mm:ss');
                    dailyActvtyMdl.updated_at = moment(dateTime).format('YYYY-MM-DD HH:mm:ss');
                } else {
                     dailyActvtyMdl.created_at_timestamp = moment().valueOf();
                     dailyActvtyMdl.created_at = moment().format('YYYY-MM-DD HH:mm:ss');
                     dailyActvtyMdl.updated_at = moment().format('YYYY-MM-DD HH:mm:ss');
                }
               
                dailyActvtyMdl.save();
              }
            });
          }

          DataModel.findOne({macid:json.MacId, "data.data.Contain":json.data.Contain}, {}, { sort: { 'created_at_timestamp' : -1 } }, function(err, lastDatas) {
            if(lastDatas && lastDatas.created_at){
              var now = moment(new Date());
              var end = moment(lastDatas.created_at);
              var duration = moment.duration(now.diff(end));
              min = duration.asMinutes(); 
              console.log('min',min);

              if(json.data.Contain == 'Sub_System_calculated'){
                DataModel.findOne({macid:json.MacId, "data.data.Contain":"System_calculated"}, {}, { sort: { 'created_at_timestamp' : -1 } }, function(err1, lastDatas1) {
                  if(lastDatas1){
                    var sysCal = lastDatas1.data.data;
                    var dataToAppend = json.data;
                    delete dataToAppend['Contain'];
                    var mergedsysCal = Object.assign(sysCal, dataToAppend);

                    lastDatas1.data.data = mergedsysCal;
                    
                    lastDatas1.markModified('data.data');
                    lastDatas1.save();

                  }
                });
              } else if(json.data.Contain == 'Sub_Warning'){
                DataModel.findOne({macid:json.MacId, "data.data.Contain":"Warning"}, {}, { sort: { 'created_at_timestamp' : -1 } }, function(err1, lastDatas1) {
                  if(lastDatas1){
                    var sysCal = lastDatas1.data.data;
                    var dataToAppend = json.data;
                    delete dataToAppend['Contain'];
                    var mergedsysCal = Object.assign(sysCal, dataToAppend);

                    lastDatas1.data.data = mergedsysCal;
                    
                    lastDatas1.markModified('data.data');
                    lastDatas1.save();

                  }
                });
              } else if(json.data.Contain == 'Sub_Notification'){
                DataModel.findOne({macid:json.MacId, "data.data.Contain":"Notification"}, {}, { sort: { 'created_at_timestamp' : -1 } }, function(err1, lastDatas1) {
                  if(lastDatas1){
                    var sysCal = lastDatas1.data.data;
                    var dataToAppend = json.data;
                    delete dataToAppend['Contain'];
                    var mergedsysCal = Object.assign(sysCal, dataToAppend);

                    lastDatas1.data.data = mergedsysCal;
                    
                    lastDatas1.markModified('data.data');
                    lastDatas1.save();

                  }
                });
              } else {
                if(json && json.data && json.data.Contain && json.data.Contain == 'alarm_warning_details' && json.alarm_warning != 0){
                  dataMdl.save();
                } else if(min > 5){
                  dataMdl.save();
                }
              }

              
            } else {
              dataMdl.save();
            }
            
          });
      } else if(!invInfo){
        console.log('Inver not found ====>>>>')
      }
    });
    

    if(json && json.data && json.data.Contain && json.data && json.data.Contain == 'DEVICE_VARIFIED'){
      // verify new device here
        const query = {macid:json.MacId};
        var updateVerification = {};
        updateVerification = {is_verified:1, verified:json.data.Contain};
        /*InverterModel.findOneAndUpdate(query, updateVerification, {upsert: false}, function(err, doc) {
          if (err){
            console.log(err);
          }
        });*/
        query.is_verified = 0;
        InverterModel.updateMany(query, updateVerification, function(err, doc) {
          if (err){
            console.log(err);
          }
        });
    } else if(json && json.content == 'inverter_menu_details'){
        const query = {control_card_no:json.Control_card_sn};
        InverterModel.findOne(query, function(err, doc) {
          if (err){
            console.log(err);
          }
          if(doc){
            let query = {inverter_id: doc._id};
            let data = {};

            data.vac_min = json.vac_min;
            data.vac_max = json.vac_max;
            data.vac_min_slow = json.vac_min_slow;
            data.vac_max_slow = json.vac_max_slow;

            data.fac_min = json.fac_min;
            data.fac_max = json.fac_max;
            data.fac_min_slow = json.fac_min_slow;
            data.fac_max_slow = json.fac_max_slow;

            data.grid_10min_high = json.grid_10min_high;

            let update = { 
              $setOnInsert: {
                inverter_id: doc._id,
                data: data,
              }
            };
              
            let options = { upsert: true };
            InverterSettingsModel.findOneAndUpdate(query, update, options)
            .catch(error => console.error(error));
          }
        });
    }

  }
  

});

client.on('error', function (error) {
  console.log('Error => ', error);
});

client.on('connect', function (data) {
  console.log('connect => ', data);
});

client.on('disconnect', function (data) {
  console.log('disconnect => ', data);
});

client.on('end', function (data) {
  console.log('end => ', data);
});


router.post('/write_data', function (req, res, next) {
  var msgData = JSON.stringify(req.body);
  client.publish('write_data', msgData, function (err) {
    if (err) {
      console.log('ERROR write_data route =>', err);
    }
  });
  res.json({status: true , message:'sendEventData sent.'});
});

router.post('/write_data2', function (req, res, next) {
  //var msgData = JSON.stringify(req.body);
  var msgData = req.body;
  const dataMdl = new DataModel;
  dataMdl.topic = msgData.topic ? msgData.topic : '';
  dataMdl.data = msgData;
  dataMdl.status = 1;
  dataMdl.created_at_timestamp = moment().valueOf();
  dataMdl.created_at = moment().format('YYYY-MM-DD HH:mm:ss');
  dataMdl.updated_at = moment().format('YYYY-MM-DD HH:mm:ss');
  dataMdl.save( (ss)=>{
    console.log('ENTRY..',dataMdl );
  });

  res.json({status: true , message:'write_data2 sent.'});
});

router.post('/write_data_list', async function (req, res, next) {
  const firstdate = moment().startOf('month');
  const lastdate=moment().endOf('month'); 
  let dataMdl = await DataModel.find({
    $and: [
      {
        created_at: {
            $gte: firstdate,
            $lt: lastdate
        }
      },
    ],
  });
  res.json({status: true, message:'data listed.', data: dataMdl});
});

router.post('/add_inverter', async function (req, res, next) {
  const post = req.body;
  if(post.apiKey && post.apiKey == apiKey){
    if(post.user_id && post.macid && post.macid){
      const user = await UserModel.findOne({'_id':post.user_id});
      if(!user){
        return res.json({status: false, message:'Invalid user.'});
      }
      //let inverter = await InverterModel.findOne({user_id_str:post.user_id, macid:post.macid});
      let inverter = await InverterModel.findOne({macid:post.macid});
      if(inverter){
          if(inverter.status == 1){
            return res.json({status: false, message:'This inverter already added.'});
          }

          inverter.macid = post.macid;
          inverter.site_name = post.site_name ? post.site_name : '';
          inverter.updated_at = moment().format('YYYY-MM-DD HH:mm:ss');
          inverter.save();

          let writeObj = {};
          writeObj.MacId =  post.macid;
          writeObj.data = {
            "Contain":"VERIFY_DEVICE"
          };
          var msgData = JSON.stringify(writeObj);
          var topic = 'write_data/'+post.macid;

          console.log('topic ===>',topic);
          console.log('add inverter write data ===>',msgData);
          client.publish(topic, msgData, function (err) {
            if (err) {
              console.log('ERROR write_data route =>', err);
            }
          });

      } else {
        /*
        inverter = new InverterModel;
        inverter.user_id = post.user_id;
        inverter.user_id_str = post.user_id;
        inverter.macid = post.macid;
        inverter.site_name = post.site_name ? post.site_name : '';
        inverter.status = 0;
        inverter.created_at_timestamp = moment().valueOf();
        inverter.created_at = moment().format('YYYY-MM-DD HH:mm:ss');
        inverter.updated_at = moment().format('YYYY-MM-DD HH:mm:ss');
        inverter.save( (ss)=>{
          let writeObj = {};
          writeObj.MacId =  post.macid;
          writeObj.data = {
            "Contain":"VERIFY_DEVICE"
          };
          var msgData = JSON.stringify(writeObj);
          var topic = 'write_data/'+post.macid;

          console.log('topic ===>',topic);
          console.log('add inverter write data ===>',msgData);
          client.publish(topic, msgData, function (err) {
            if (err) {
              console.log('ERROR write_data route =>', err);
            }
          });

        });
        */
      }
      return res.json({status: true, message:'Data added successfully.', data: inverter});
    } else {
      return res.json({status: false, message:'Missing required data.'});
    }
  } else {
    return res.json({status: false, message:'Invalid request.'});
  }
  
});

router.post('/get_inverter_detail', async function (req, res, next) {
  const post = req.body;
  if(post.apiKey && post.apiKey == apiKey){
    if(post.user_id && post.inverter_id){
      const user = await UserModel.findOne({'_id':post.user_id});
      if(!user){
        return res.json({status: false, message:'Invalid user.'});
      }
      let inverter = await InverterModel.findOne({_id:post.inverter_id});
      if(inverter){
        return res.json({status: true, message:'Data listed successfully.', data: inverter});
      } else {
        return res.json({status: false, message:'Data not found.', data: inverter});
      }
      
    } else {
      return res.json({status: false, message:'Missing required data.'});
    }
  } else {
    return res.json({status: false, message:'Invalid request.'});
  }
  
});

router.post('/save_inverter_settings', async function (req, res, next) {
  const post = req.body;
  if(post.apiKey && post.apiKey == apiKey){
    if(post.user_id && post.setting_save_type && post.inverter_id){
      const user = await UserModel.findOne({'_id':post.user_id});
      if(!user){
        return res.json({status: false, message:'Invalid user.'});
      }
      var inverter = await InverterModel.findOne({_id:post.inverter_id});
      if(!inverter){
        return res.json({status: false, message:'Invalid inverter id.'});
      }

      let options = { sort: { created_at_timestamp: -1 } };
      var filter = { "data.Control_card_sn" : inverter.control_card_no };
      var setting;
      var writeCmd;
      if(post.setting_save_type == 'inverter_start_stop'){
        if(post.inverter_start_stop == 0 || post.inverter_start_stop == 1){
          const update = { "data.inverter_start_stop": post.inverter_start_stop };
          filter["data.content"] = 'inverter_menu_details';
          setting = await DataModel.findOneAndUpdate(filter, update, options);
          writeCmd = {"Control_card_sn":inverter.control_card_no,"data":1,"inverter_start_stop":post.inverter_start_stop};
        } else {
          return res.json({status: false, message:'Invalid inverter_start_stop.'});
        }
        
      } else if(post.setting_save_type == 'meter'){
        if(post.meter_en_di != 0 && post.meter_en_di != 1){
          return res.json({status: false, message:'Invalid meter_en_di.'});
        }
        if(parseInt(post.meter_1id) < 0 || parseInt(post.meter_1id) > 200){
          return res.json({status: false, message:'Invalid meter_1id.'});
        }
        if(parseInt(post.meter_2id) < 0 || parseInt(post.meter_2id) > 200){
          return res.json({status: false, message:'Invalid meter_2id.'});
        }
        const update = { "data.meter_en_di" : post.meter_en_di,
                          "data.meter_1id" : post.meter_1id,
                          "data.meter_2id" : post.meter_2id
                        };
        filter["data.content"] = 'inverter_menu_details';
        setting = await DataModel.findOneAndUpdate(filter, update, options);
        writeCmd = {"Control_card_sn":inverter.control_card_no,"data":2,"meter_en_di":post.meter_en_di,"meter_1id":post.meter_1id,"meter_2id":post.meter_2id};
      } else if(post.setting_save_type == 'grid'){

          if(parseInt(post.vac_min) < 150 || parseInt(post.vac_min) > 300){
            return res.json({status: false, message:'Invalid vac_min.'});
          }
          if(parseInt(post.vac_max) < 150 || parseInt(post.vac_max) > 300){
            return res.json({status: false, message:'Invalid vac_max.'});
          }
          if(parseInt(post.vac_min_slow) < 150 || parseInt(post.vac_min_slow) > 300){
            return res.json({status: false, message:'Invalid vac_min_slow.'});
          }
          if(parseInt(post.vac_max_slow) < 150 || parseInt(post.vac_max_slow) > 300){
            return res.json({status: false, message:'Invalid vac_max_slow.'});
          }

          if(parseInt(post.fac_min) < 40 || parseInt(post.fac_min) > 65){
            return res.json({status: false, message:'Invalid fac_min.'});
          }
          if(parseInt(post.fac_max) < 40 || parseInt(post.fac_max) > 65){
            return res.json({status: false, message:'Invalid fac_max.'});
          }
          if(parseInt(post.fac_min_slow) < 40 || parseInt(post.fac_min_slow) > 65){
            return res.json({status: false, message:'Invalid fac_min_slow.'});
          }
          if(parseInt(post.fac_max_slow) < 40 || parseInt(post.fac_max_slow) > 65){
            return res.json({status: false, message:'Invalid fac_max_slow.'});
          }

          if(parseInt(post.grid_10min_high) < 150 || parseInt(post.grid_10min_high) > 300){
            return res.json({status: false, message:'Invalid grid_10min_high.'});
          }

          const update = {  
                        "data.vac_min.value" : post.vac_min,
                        "data.vac_max.value" : post.vac_max,
                        "data.vac_min_slow.value" : post.vac_min_slow,
                        "data.vac_max_slow.value" : post.vac_max_slow,
                        "data.fac_min.value" : post.fac_min,
                        "data.fac_max.value" : post.fac_max,
                        "data.fac_min_slow.value" : post.fac_min_slow,
                        "data.fac_max_slow.value" : post.fac_max_slow,
                        "data.grid_10min_high.value" : post.grid_10min_high
                        };
          filter["data.content"] = 'inverter_menu_details';
          setting = await DataModel.findOneAndUpdate(filter, update, options);
          writeCmd = {"Control_card_sn":inverter.control_card_no,"data":3,"vac_min":post.vac_min,"vac_max":post.vac_max,"fac_min":post.fac_min,"fac_max":post.fac_max,"vac_min_slow":post.vac_min_slow,"vac_max_slow":post.vac_max_slow,"fac_min_slow":post.fac_min_slow,"fac_max_slow":post.fac_max_slow,"grid_10min_high":post.grid_10min_high};
      } else if(post.setting_save_type == 'battery_charge_discharge_max'){
          if(parseInt(post.battery_charge_max_current) < 0 || parseInt(post.battery_charge_max_current) > 25){
            return res.json({status: false, message:'Invalid battery_charge_max_current.'});
          }
          if(parseInt(post.battery_discharge_max_current) < 0 || parseInt(post.battery_discharge_max_current) > 25){
            return res.json({status: false, message:'Invalid battery_discharge_max_current.'});
          }
          const update = { 
            "data.battery_charge_max_current.value": post.battery_charge_max_current,
            "data.battery_discharge_max_current.value": post.battery_discharge_max_current,
          };
          filter["data.content"] = 'battery_menu_details';
          setting = await DataModel.findOneAndUpdate(filter, update, options);
          writeCmd = {"Control_card_sn":inverter.control_card_no,"data":4,"battery_charge_max_current":post.battery_charge_max_current,"battery_discharge_max_current":post.battery_discharge_max_current};
      } else if(post.setting_save_type == 'battery_min_capcity'){
          if(parseInt(post.battery_min_capcity) < 0 || parseInt(post.battery_min_capcity) > 100){
            return res.json({status: false, message:'Invalid battery_min_capcity.'});
          }
          const update = { "data.battery_min_capcity.value" : post.battery_min_capcity };
          filter["data.content"] = 'battery_menu_details';
          setting = await DataModel.findOneAndUpdate(filter, update, options);
          writeCmd = {"Control_card_sn":inverter.control_card_no,"data":5,"battery_min_capcity":post.battery_min_capcity};
      } else if(post.setting_save_type == 'grid_tie_limit_en_di'){
          if(post.grid_tie_limit_en_di != 0 && post.grid_tie_limit_en_di != 1){
            return res.json({status: false, message:'Invalid grid_tie_limit_en_di.'});
          }
          if(parseInt(post.discharge_min_capcity) < 10 || parseInt(post.discharge_min_capcity) > 100){
            return res.json({status: false, message:'Invalid discharge_min_capcity.'});
          }
          const update = { 
            "data.grid_tie_limit_en_di" : post.grid_tie_limit_en_di,
            "data.discharge_min_capcity.value" : post.discharge_min_capcity,
            "data.dischCutOffCapacity_GridMode.value" : post.discharge_min_capcity
          };
          filter["data.content"] = 'battery_menu_details';
          setting = await DataModel.findOneAndUpdate(filter, update, options);
          writeCmd = {"Control_card_sn":inverter.control_card_no,"data":6,"grid_tie_limit_en_di":post.grid_tie_limit_en_di,"discharge_min_capcity":post.discharge_min_capcity};
      } else if(post.setting_save_type == 'operating_mode'){
          if(parseInt(post.operating_mode) < 0 || parseInt(post.operating_mode) > 3){
            return res.json({status: false, message:'Invalid operating_mode.'});
          }
          const update = { 
            "data.operating_mode" : post.operating_mode
          };
          filter["data.content"] = 'power_control_menu_details';
          setting = await DataModel.findOneAndUpdate(filter, update, options);
          writeCmd = {"Control_card_sn":inverter.control_card_no,"data":8,"operating_mode":post.operating_mode};
      } else if(post.setting_save_type == 'charge_period_1'){
          if(parseInt(post.start_time_hr) < 0 || parseInt(post.start_time_hr) > 23){
            return res.json({status: false, message:'Invalid start_time_hr.'});
          }
          if(parseInt(post.start_time_mi) < 0 || parseInt(post.start_time_mi) > 59){
            return res.json({status: false, message:'Invalid start_time_mi.'});
          }
          if(parseInt(post.end_time_hr) < 0 || parseInt(post.end_time_hr) > 23){
            return res.json({status: false, message:'Invalid end_time_hr.'});
          }
          if(parseInt(post.end_time_mi) < 0 || parseInt(post.end_time_mi) > 59){
            return res.json({status: false, message:'Invalid end_time_mi.'});
          }
          if(parseInt(post.CP1_max_cap) < 5 || parseInt(post.CP1_max_cap) > 100){
            return res.json({status: false, message:'Invalid CP1_max_cap.'});
          }
          const update = { 
            "data.charge_period_1.start_time_hr" : parseInt(post.start_time_hr),
            "data.charge_period_1.start_time_mi" : parseInt(post.start_time_mi),
            "data.charge_period_1.end_time_hr" : parseInt(post.end_time_hr),
            "data.charge_period_1.end_time_mi" : parseInt(post.end_time_mi),
            "data.CP1_max_cap" : post.CP1_max_cap
          };
          filter["data.content"] = 'power_control_menu_details';
          setting = await DataModel.findOneAndUpdate(filter, update, options);
          writeCmd = {"Control_card_sn":inverter.control_card_no,"data":9,"start_time_hr":post.start_time_hr,"start_time_mi":post.start_time_mi,"end_time_hr":post.end_time_hr,"end_time_mi":post.end_time_mi,"CP1_max_cap":post.CP1_max_cap};
      } else if(post.setting_save_type == 'charge_period_2'){
          if(parseInt(post.start_time_hr) < 0 || parseInt(post.start_time_hr) > 23){
            return res.json({status: false, message:'Invalid start_time_hr.'});
          }
          if(parseInt(post.start_time_mi) < 0 || parseInt(post.start_time_mi) > 59){
            return res.json({status: false, message:'Invalid start_time_mi.'});
          }
          if(parseInt(post.end_time_hr) < 0 || parseInt(post.end_time_hr) > 23){
            return res.json({status: false, message:'Invalid end_time_hr.'});
          }
          if(parseInt(post.end_time_mi) < 0 || parseInt(post.end_time_mi) > 59){
            return res.json({status: false, message:'Invalid end_time_mi.'});
          }
          if(parseInt(post.CP2_max_cap) < 5 || parseInt(post.CP2_max_cap) > 100){
            return res.json({status: false, message:'Invalid CP2_max_cap.'});
          }
          const update = { 
            "data.charge_period_2.start_time_hr" : parseInt(post.start_time_hr),
            "data.charge_period_2.start_time_mi" : parseInt(post.start_time_mi),
            "data.charge_period_2.end_time_hr" : parseInt(post.end_time_hr),
            "data.charge_period_2.end_time_mi" : parseInt(post.end_time_mi),
            "data.CP2_max_cap" : post.CP2_max_cap
          };
          filter["data.content"] = 'power_control_menu_details';
          setting = await DataModel.findOneAndUpdate(filter, update, options);
          writeCmd = {"Control_card_sn":inverter.control_card_no,"data":10,"start_time_hr":post.start_time_hr,"start_time_mi":post.start_time_mi,"end_time_hr":post.end_time_hr,"end_time_mi":post.end_time_mi,"CP2_max_cap":post.CP2_max_cap};
      } else if(post.setting_save_type == 'BackUp_GridChargeEN'){
          if(parseInt(post.BackUp_GridChargeEN) < 0 || parseInt(post.BackUp_GridChargeEN) > 1){
            return res.json({status: false, message:'Invalid BackUp_GridChargeEN.'});
          }
          const update = { 
            "data.BackUp_GridChargeEN" : post.BackUp_GridChargeEN
          };
          filter["data.content"] = 'power_control_menu_details';
          setting = await DataModel.findOneAndUpdate(filter, update, options);
          writeCmd = {"Control_card_sn":inverter.control_card_no,"data":12,"BackUp_GridChargeEN":post.BackUp_GridChargeEN};
      } else if(post.setting_save_type == 'backup_mode'){
          if(parseInt(post.start_time_hr) < 0 || parseInt(post.start_time_hr) > 23){
            return res.json({status: false, message:'Invalid start_time_hr.'});
          }
          if(parseInt(post.start_time_mi) < 0 || parseInt(post.start_time_mi) > 59){
            return res.json({status: false, message:'Invalid start_time_mi.'});
          }
          if(parseInt(post.end_time_hr) < 0 || parseInt(post.end_time_hr) > 23){
            return res.json({status: false, message:'Invalid end_time_hr.'});
          }
          if(parseInt(post.end_time_mi) < 0 || parseInt(post.end_time_mi) > 59){
            return res.json({status: false, message:'Invalid end_time_mi.'});
          }
          const update = { 
            "data.backup_mode.start_time_hr" : parseInt(post.start_time_hr),
            "data.backup_mode.start_time_mi" : parseInt(post.start_time_mi),
            "data.backup_mode.end_time_hr" : parseInt(post.end_time_hr),
            "data.backup_mode.end_time_mi" : parseInt(post.end_time_mi)
          };
          filter["data.content"] = 'power_control_menu_details';
          setting = await DataModel.findOneAndUpdate(filter, update, options);
          writeCmd = {"Control_card_sn":inverter.control_card_no,"data":13,"start_time_hr":post.start_time_hr,"start_time_mi":post.start_time_mi,"end_time_hr":post.end_time_hr,"end_time_mi":post.end_time_mi};
      } else if(post.setting_save_type == 'eps_system'){
          if(parseInt(post.EPS_Mute) < 0 || parseInt(post.EPS_Mute) > 1){
            return res.json({status: false, message:'Invalid EPS_Mute.'});
          }
          if(parseInt(post.EPS_Frequency) < 0 || parseInt(post.EPS_Frequency) > 1){
            return res.json({status: false, message:'Invalid EPS_Frequency.'});
          }
          if(parseInt(post.EPS_AutoRestart) < 0 || parseInt(post.EPS_AutoRestart) > 1){
            return res.json({status: false, message:'Invalid EPS_AutoRestart.'});
          }
          if(parseInt(post.EPS_MinEscSoc) < 0 || parseInt(post.EPS_MinEscSoc) > 100){
            return res.json({status: false, message:'Invalid EPS_MinEscSoc.'});
          }
          const update = { 
            "data.EPS_Mute" : post.EPS_Mute,
            "data.EPS_Frequency" : post.EPS_Frequency,
            "data.EPS_AutoRestart" : post.EPS_AutoRestart,
            "data.EPS_MinEscSoc" : post.EPS_MinEscSoc
          };
          filter["data.content"] = 'power_control_menu_details';
          setting = await DataModel.findOneAndUpdate(filter, update, options);
          writeCmd = {"Control_card_sn":inverter.control_card_no,"data":14,"EPS_Mute":post.EPS_Mute,"EPS_Frequency":post.EPS_Frequency,"EPS_AutoRestart":post.EPS_AutoRestart,"EPS_MinEscSoc":post.EPS_MinEscSoc};
      } else if(post.setting_save_type == 'Export_control_User_Limit'){
          if(parseInt(post.Export_control_User_Limit) < 0 || parseInt(post.Export_control_User_Limit) > 60000){
            return res.json({status: false, message:'Invalid Export_control_User_Limit.'});
          }
          const update = { 
            "data.Export_control_User_Limit" : parseInt(post.Export_control_User_Limit)
          };
          filter["data.content"] = 'power_control_menu_details';
          setting = await DataModel.findOneAndUpdate(filter, update, options);
          writeCmd = {"Control_card_sn":inverter.control_card_no,"data":15,"Export_control_User_Limit":post.Export_control_User_Limit};
      } else if(post.setting_save_type == 'date_time'){

          if(parseInt(post.hh) < 0 || parseInt(post.hh) > 23){
            return res.json({status: false, message:'Invalid hh.'});
          }
          if(parseInt(post.mm) < 0 || parseInt(post.mm) > 59){
            return res.json({status: false, message:'Invalid mm.'});
          }
          if(parseInt(post.ss) < 0 || parseInt(post.ss) > 59){
            return res.json({status: false, message:'Invalid ss.'});
          }

          const update = { 
            "data.date.day" : parseInt(post.day),
            "data.date.month" : parseInt(post.mon),
            "data.date.year" : parseInt(post.year),
            "data.time.hr" : parseInt(post.hh),
            "data.time.mi" : parseInt(post.mm),
            "data.time.sec" : parseInt(post.ss)
          };
          filter["data.content"] = 'date&time';
          setting = await DataModel.findOneAndUpdate(filter, update, options);
          writeCmd = {"Control_card_sn":inverter.control_card_no,"data":16,
                      "date": {"day":post.day,"month":post.mon,"year":post.year},"time":{"hr":post.hh,"mi":post.mm,"sec":post.ss}
                      };
      }

      var msgData = JSON.stringify(writeCmd);
      console.log('msgData',msgData);
      client.publish('write_data', msgData, function (err) {
        if (err) {
          console.log('ERROR save_inverter_settings route =>', err);
        }
      });
      
      return res.json({status: true, message:'Data listed successfully.', data: inverter});
      
    } else {
      return res.json({status: false, message:'Missing required data.'});
    }
  } else {
    return res.json({status: false, message:'Invalid request.'});
  }
  
});

router.get('/test_shell', async function (req, res, next) {

  const { exec } = require("child_process");

  // exec("/snap/bin novnc --listen 6082 --vnc 122.173.65.126:5901", (error, stdout, stderr) => {
  //     if (error) {
  //         console.log(`error: ${error.message}`);
  //         return;
  //     }
  //     if (stderr) {
  //         console.log(`stderr: ${stderr}`);
  //         return;
  //     }
  //     console.log(`stdout: ${stdout}`);
  // });
});


app.use(router);

 // app.listen(1337,"0.0.0.0");
 http.listen(3000, (listener) => {
    console.log('listening on *:3000 ' + listener);
});


app.use('/', index);





module.exports = app;