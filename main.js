const { app, BrowserWindow, nativeImage, ipcMain } = require("electron");

const PHPServer = require('php-server-manager');

// Habilita o live reload no Electron e no FrontEnd da aplicação com a lib electron-reload
// Assim que alguma alteração no código é feita
require("electron-reload")(__dirname, {
  // Note that the path to electron may vary according to the main file
  electron: require(`${__dirname}/node_modules/electron`),
});

const server = new PHPServer({
    php: "php\\php.exe", 
    port: 8080,
    directory: __dirname,
    directives: {
        display_errors: 0,
        expose_php: 0
    }
});
 

// Função que cria uma janela desktop
function createWindow() {
    server.run();
  // Adicionando um ícone na barra de tarefas/dock
  const icon = nativeImage.createFromPath(`${app.getAppPath()}/assets/image/logo-madefy.png`);

  if (app.dock) {
    app.dock.setIcon(icon);
  }

  // Cria uma janela de desktop
  const win = new BrowserWindow({
    icon,
    width: 800,
    height: 600,
    webPreferences: {
      // habilita a integração do Node.js no FrontEnd
      nodeIntegration: true,
      contextIsolation: false
    },
  });

  // carrega a janela com o conteúdo dentro de index.html
  //win.loadFile("index.html");
  win.loadURL("http://" + server.host + ":" + server.port + "/");

  const { shell } = require("electron");
  shell.showItemInFolder("fullPath");

   //win.webContents.openDevTools();
}

app.whenReady().then(createWindow);

app.on("window-all-closed", () => {
  
  if (process.platform !== "darwin") {
    server.close();
    app.quit();
  }
});

app.on("activate", () => {
  // Esse evento é disparado pelo MacOS quando clica no ícone do aplicativo no Dock.
  // Basicamente cria a janela se não foi criada.
  if (BrowserWindow.getAllWindows().length === 0) {
    createWindow();
  }
});
