// javascript clock

var UR_ELM;

function UR_Init()
{
        UR_ELM = document.getElementById('ur');
        if(UR_ELM) {
            UR_Start();
        } else {
            setTimeout(UR_Init, 20);
        }
}
function UR_Start() {
        if(UR_ELM) {
      UR_Nu = new Date();
      UR_Indhold = showFilled(UR_Nu.getHours()) + ":" + showFilled(UR_Nu.getMinutes()) + ":" + showFilled(UR_Nu.getSeconds());
      UR_ELM.innerHTML = UR_Indhold;
      setTimeout(UR_Start, 1000);
        }
}
function showFilled(Value) {
    return (Value > 9) ? "" + Value : "0" + Value;
}

UR_Init();

function INFOSCREEN_INIT() { // General "interface" for infoscreen - need to retrigger clock on bulletin change
  UR_Init();
}