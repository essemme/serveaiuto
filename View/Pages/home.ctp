<div class="container">

    <!-- Example row of columns -->
      <div class="row">
        <div class="span4">
          <h2>Richieste</h2>
          <p> Puoi esaminare tutte le richieste, o per tipo.
          <a class="btn" href="/richieste">Tutte le richieste</a>
          </p>
          <h2>
              Accedi
          </h2>            
          <p>
          Per interagire devi accedere al sito.<br />
          <a class="btn" href="/login">Entra</a> (anche usando facebook) 
          </p>
        </div>
        <div class="span4">
          <h2>Ultime richieste per tipo</h2>
          <ul>
              <li><a class="btn" href="/richieste/index/tipo:beni">Esamina richieste</a> di beni di prima necessità</li>
              <li><a class="btn" href="/richieste/index/tipo:strumenti">Esamina richieste</a> di strumenti e supporti</li>
              <li><a class="btn" href="/richieste/index/tipo:volontari">Esamina richieste</a> di volontari, tempo, lavoro</li>
              <li><a class="btn" href="/richieste/index/tipo:altro">Esamina richieste</a> di altro tipo (alloggio, etc.)</li>
          </ul>
       </div>
        <div class="span4">
          <h2>Offerte</h2>
           Dopo esserti registrato, puoi segnalare le tue offerte e disponibilità.<br/>
          <a class="btn" href="/offerte">Tutte le tue offerte </a>     <br/>
          <a class="btn" href="/offerte/add">Aggiungi offerta </a>    
        </div>
      </div>
    
      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="hero-unit">
          <p><?php echo $this->Facebook->like(); 
            echo ' ' . $this->Facebook->sendbutton();?></p>
        <h1>Emergenza terremoto Emilia Romagna</h1><br>
        <p> La risposta al sisma che il 20 e 29 maggio 2012 ha colpito duramente la regione è enorme.</p>
        <p> Oltre al lavoro della Protezione civile e delle autorità locali e nazionali, un fiume in piena di 
            richieste ed offerte di aiuto di vario tipo, da parte di associazioni e singoli, si muove attraverso 
            mille canali -contatti personali, telefono, email, social networks.
        </p>
        <p> Questo sito cerca di favorire l'incontro, mettendo un po' di ordine tra le richieste 
            ed offerte che si susseguono, arrivando a volte ormai superate, o con recapiti e numeri di telefono 
            che andrebbero verificati. (si rischia, ad esempio, di intasare linee che dovrebbero restare libere)
        </p>
        <p> Cerchiamo di ridurre il rischio di disperdere parte di questa grande ondata di solidarietà. 
            I Centri di Servizio per il Volontariato delle province colpite, con modalità diverse a seconda della provincia ma 
            comunque in rete, sono a disposizione per fungere da "filtro" ove possibile.
        </p>
        <p> ATTENZIONE - nella fase di emergenza è molto difficile accettare volontari singoli, non appartenenti ad associazioni di protezione civile, privi di formazione. Ma possono esserci attività complementari e necessità che si protrannao nel tempo, oltre l'emergenza.
        </p>
        <p> Registrati ed <?php echo $this->Html->link('accedi', array('controller' => 'users', 'action' => 'login'), array('class' => 'btn')) ?> per partecipare! 
            <br/>
            I numeri di cellulare e altri dettagli non sono visibili a chi non è registrato.<br/>
            Sarà presto possibile usare il proprio profilo facebook come utente del sito.
        </p>
        <p> Nota: questo sito è frutto di <strike>due giorni</strike> quattro giorni di lavoro, principalmente ad uso interno, ed è un work in progress: partiamo con la parte delle richieste; aggiungeremo presto nuove opzioni per gli utenti registrati, l'integrazione con facebook e la sezione delle offerte</p>
        <p>Aggionramento: stiamo aggiungendo funzioni per facilitare il lavoro, un grosso aiuto è venuto da <a href="http://hackathonterremoto.wordpress.com">Hackathon Terremoto</a> (presto i ringraziamenti a tutti coloro che hanno contribuito) </p>
        <p> Per informazioni vedere il <a href="http://terremoto.volontariamo.it">sito regionale dedicato</a> a cura del CSV di Modena in collaborazione coi CSV dell'Emilia Romagna.
        
        <p> Per altre informazioni vedere anche su <a href="http://www.ferrarasociale.org/csv/news/2012/05/5241:Emergenza_terremoto_come_aiutare_ed_altre_informazioni">ferrarasociale.org</a> e gli <a href="http://miriguarda.it"> annunci su miriguarda.it</a></p>
        <p> Per informazioni, segnalare problemi col sito, o chiedere pubblicazione o modifiche di richieste pubblicate, scrivere ad <a href="mailto:info@csvferrara.it">info@csvferrara.it</a> e <a href="mailto:segreteria@csvferrara.it">segreteria@csvferrara.it</a></p>
        
        <?php //echo $this->element('RequirementChecker'); ?>
      </div>

      <hr>

      <footer>
        <p>Un instant project di Agire Sociale CSV Ferrara - in Collaborazione con CSV Modena, Reggio Emilia, Bologna, Mantova </p>
      </footer>

    </div> <!-- /container -->