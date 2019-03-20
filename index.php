<?php error_reporting(0); ?>
<html>
    <head>
        <title>VLC Extended</title>
        <script src='includes/js/jquery.min.js'></script>
        <link rel="stylesheet" href='includes/css/jquery-ui.min.css' />
        <script src='includes/js/jquery-ui.min.js'></script>
        <script src='includes/js/clappr.min.js'></script>
        <script src='includes/js/moment.min.js'></script>
        <style>
            body {
                font-family: arial;
                background-color: #111111;
                color: white;
            }
            table {
                margin-top: 15px;
                width: 100%;
                text-align: center;
                overflow:hidden;
                border-collapse:collapse;
                border: solid #ccc 1px;
                -webkit-border-radius: 15px;
                -moz-border-radius: 15px;
                border-radius: 15px;
            }
            td, th, h3 {
                -moz-user-select: -moz-none;
                -khtml-user-select: none;
                -webkit-user-select: none;
            }
            .modal {
                display: none;
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgb(0,0,0);
                background-color: rgba(0,0,0,0.4);
            }
            .modal-content {
                background-color: #fefefe;
                margin: 15% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 50%;
            }
        </style>
    </head>
    <body>
    <center>Para adicionar mais arquivos de midia, mova-os para o diretorio: C:/xampp3/htdocs/here/files/videos.<br/><b>Arquivos de Midia</b></center><br/>
    <div style='background-color: #333333;border-radius: 15px;width:100%;margin: 0 auto;left:0;display: table;'>
        <?php
            $videos = glob("C:/xampp3/htdocs/here/files/videos/*.{mp4,mkv,avi,mov,wmv}", GLOB_BRACE);
            foreach($videos as $video) {
                $time = exec("ffmpeg -i ".$video." 2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,//");
                $temp_file = tempnam(sys_get_temp_dir(), 'thumbnail_');
                rename($temp_file, $temp_file .= '.png');
                $seconds = (explode(':', $time)[0]*60) + (explode(':', $time)[1]*60) + explode(':', $time)[2];
                $cmd = 'ffmpeg -y -ss '.number_format($seconds/10, 0, '', '').' -i '.$video.' -vframes 1 -q:v 2 '.$temp_file.' 2>&1';
                shell_exec($cmd );
                $content = 'data:image/png;base64,'.base64_encode(file_get_contents($temp_file));
                echo "<div style='float:left;padding: 20px;width:25%;display: table-row' class='video'><center><img src='".$content."' style='width:256px;height:144px;border-radius: 15px;' />";
                $aux = explode("/", $video);
                echo "<br/><h3 class='videonome'>".end($aux)."</h3>";
                echo "<h3 class='videotempo'>".$time."</h3></center></div>";
            }
        ?>
        </div>
        <br/>
        <center><h3>Previa da midia(Apenas MP4)</h3></center>
        <center><div id="player" style='display: none;'></div></center>
        <br/>
        <script>
            var player = new Clappr.Player({
                source: "<?php echo "./files/videos/".end(explode("/", $videos[0])); ?>",
                mimeType: 'video/mp4',
                mute: false,
                parentId: "#player"
            });
        </script>
        <center>
            <div style='width: 100%;display: block;'>
                <button id='vlmconf'>Agendar Transmissao</button>
            </div>
        </center>
        <br/>
        <center>
            <div style='width: 100%;display: block;'>
                <button id='logoconf'>Agendar Logo</button>
            </div>
        </center>
        <br/>
        <center>
            <div style='width: 100%;display: block;'>
                <button id='subconf'>Agendar Exibição de texto</button>
            </div>
        </center>
        <br/>
        <div id='logoconfdiv' class='modal'>
            <div class='modal-content'>
                <form action='telnet/subfilter.php' method='get'>
                    <input type='hidden' name='acao' value='Logo'/>
                    <br/>
                    <p style='color:black;'>Data da programação:</p> <input type='datetime-local' name='timefrom' style='width:100%' step='1'/><br/>
                    <br/>
                    <p style='color:black;'>Data de término:</p> <input type='datetime-local' name='timeto' style='width:100%' step='1'/><br/>
                    <br/>
                    <p style='color:black;'>Posição</p>
                    <select name='position' style='width:100%;'>
                        <option value='0'>Centro</option>
                        <option value='1'>Esquerda</option>
                        <option value='2'>Direita</option>
                        <option value='4'>Centro Superior</option>
                        <option value='5'>Superior Esquerdo</option>
                        <option value='6'>Superior Direito</option>
                        <option value='8'>Centro Inferior</option>
                        <option value='9'>Inferior Esquerdo</option>
                        <option value='10'>Inferior Direito</option>
                    </select>
                    <br/>
                    <?php
                    $videos = glob("C:/xampp3/htdocs/here/files/images/*.{mp4,mkv,avi,mov,wmv}", GLOB_BRACE);
                        foreach($videos as $video) {
                            $time = exec("C:/xampp3/htdocs/ffmpeg -i ".$video." 2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,//");
                            echo "<input type='radio' name='address' value='$video'/><b style='color: black;'>".end(explode("/", $video))."</b><br/>";
                        }
                    ?>
                        <br/>
                    <input type='submit'/>
                </form>
            </div>
        </div>
        <div id='subconfdiv' class='modal'>
            <div class='modal-content'>
                <form action='telnet/subfilter.php' method='get'>
                    <input type='hidden' name='acao' value='Texto'/>
                    <textarea name='text' placeholder='Texto' style='width:100%;'></textarea>
                    <br/>
                    <p style='color:black;'>Data da programação:</p> <input type='datetime-local' name='timefrom' style='width:100%' step='1'/><br/>
                    <br/>
                    <p style='color:black;'>Por quanto tempo (em segundos) (0 dura para sempre): </p>
                    <input type='number' value='0' style='width:100%;' name='timeout'/>
                    <p style='color:black;'>Posição</p>
                    <select name='position' style='width:100%;'>
                        <option value='0'>Centro</option>
                        <option value='1'>Esquerda</option>
                        <option value='2'>Direita</option>
                        <option value='4'>Centro Superior</option>
                        <option value='5'>Superior Esquerdo</option>
                        <option value='6'>Superior Direito</option>
                        <option value='8'>Centro Inferior</option>
                        <option value='9'>Inferior Esquerdo</option>
                        <option value='10'>Inferior Direito</option>
                    </select>
                    
                    <br/>
                    <br/>
                    <input type='submit'/>
                </form>
            </div>
        </div>
        <div id='vlmconfdiv' class='modal'>
            <div class='modal-content'>
                <form action='telnet/vlm.php' method='get'>
                    <input type='text' name='name' style='width:100%' placeholder='Nome do agendamento'/><br/>
                    <br/>
                    <p style='color:black;'>Data/Hora da programação: </p><input type='datetime-local' name='time' style='width:100%' step='1'/><br/>
                    <br/>
                    <?php
                    $videos = glob("C:/xampp3/htdocs/here/files/videos/*.{mp4,mkv,avi,mov,wmv}", GLOB_BRACE);
                        foreach($videos as $video) {
                            $time = exec("C:/xampp3/htdocs/ffmpeg -i ".$video." 2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,//");
                            echo "<input type='checkbox' name='address' value='$video'/><b style='color: black;'>".end(explode("/", $video))."</b><br/>";
                        }
                    ?>
                        <br/>
                    <input type='submit'/>
                </form>
            </div>
        </div>
        <div>
            <?php
                include "telnet/telnet.php";
                $con = new Telnet();
                $schedules = $con->sendCommand("show");
                $here = explode('enabled : yes', str_replace('next launch', '', explode('Unknown', substr($schedules, strpos($schedules, 'schedule')+10))[0]));
                echo "<center><b>Agendamentos</b></center><br/><table style='width:75%;background-color: #333333; margin: 0 auto;'>
                <tr style='text-align: center;background-color: #ffffff;color: #111111;'>
                    <th>Nome do Agendamento</th>
                    <th>Data programada</th>
                    <th>Termino</th>
                </tr>";
                for($i = 0; $i < sizeof($here)-1;$i++) {
                    if($i == 0) {
                        $scheduleinfo = $con->sendCommand("show ".trim($here[$i]));
                    } else {
                        $scheduleinfo = $con->sendCommand("show ".trim(explode(')', $here[$i])[1]));
                    }
                    $data = Date('d/m/Y H:i:s', strtotime(explode('(', str_replace(' : ', '', $here[$i+1]))[0]));
                    $dataformatada = Date('F d, Y H:i:s', strtotime(explode('(', str_replace(' : ', '', $here[$i+1]))[0]));
                    
                    if(strtotime(explode('(', str_replace(' : ', '', $here[$i+1]))[0]) != time() && strtotime(explode('(', str_replace(' : ', '', $here[$i+1]))[0]) != strtotime()) {
                        if($i == 0) {
                            $nome = $here[$i];
                            echo "<tr style='text-align: center;' class='programacao'>
                                <td>".$nome."</td>
                                <td class='datainicio' alt='".$dataformatada."'>".$data."</td>";
                        } else {
                            $nome = explode(')', $here[$i])[1];
                            echo "<tr style='text-align: center;' class='programacao'>
                                    <td>".$nome."</td>
                                    <td class='datainicio' alt='".$dataformatada."'>".$data."</td>";
                        }
                        echo "<td class='datafinalprograma' alt='".explode(' play', explode('control ', $scheduleinfo)[1])[0]."'></td>
                        </tr>";
                    }
                }
                echo "</table>";
            ?>
        </div>
        <script>
            var modal = document.getElementById('vlmconfdiv');
            var btn = document.getElementById("vlmconf");
            btn.onclick = function () {
                modal.style.display = "block";
            }
            var logodiv = document.getElementById('logoconfdiv');
            var logo = document.getElementById("logoconf");
            logo.onclick = function () {
                logodiv.style.display = "block";
            }
            window.onclick = function (event) {
                if (event.target == logodiv) {
                    logodiv.style.display = "none";
                } else if (event.target == subdiv) {
                    subdiv.style.display = "none";
                } else if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
            var subdiv = document.getElementById('subconfdiv');
            var sub = document.getElementById("subconf");
            sub.onclick = function () {
                subdiv.style.display = "block";
            }
            $(document).ready(function() {
                $( ".modal-content" ).draggable();
                $('.modal-content').hover(function() {
                    $(this).css('cursor', 'move');
                });
                $('.video').click(function() {
                    $('#player').show(250);
                    player.configure({
                        source: 'files/videos/'+$(this).find('.videonome').text()
                    });
                    player.play();
                });
                $('.video').hover(function () {
                    $(this).css( 'cursor', 'pointer');
                    $(this).find('img').css('opacity', 0.5);
                }, function () {
                    $(this).find('img').css('opacity', 1);
                });
                $('.programacao').each(function() {
                    var este = $(this);
                    $('.video').each(function(index) {
                        if($(this).find('.videonome').text().split('.')[0] == este.find('.datafinalprograma').attr('alt')) {
                            let data = new Date(este.find('.datainicio').attr('alt'));
                            let aux = $(this).find('.videotempo').text().split(':');
                            let allofit = parseFloat(aux[0]*3600)+parseFloat(aux[1]*60)+parseFloat(aux[2]);
                            console.log(allofit);
                            let dataplus = moment(data).add(allofit, 'seconds').toDate();
                            console.log(dataplus);
                            este.find('.datafinalprograma').text(moment(dataplus).format('DD/MM/YYYY HH:mm:ss'));
                        }
                    });
                });
            });
        </script>
    </body>
</html>