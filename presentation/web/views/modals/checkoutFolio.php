<?php
    /** Checkout modal File for Folio page
     * @package web @subpackage templates */
    /**
     * @author https://github.com/maparrar/maqinato
     * @package web
     * @subpackage Modal
     */
?>
<div id="modalCheckout">
<!-- MODAL GENERATOR -->
    <div id="check-out">
        <h2 id="title">Give to the cause mix of this folio</h2>
        <div class="section">
            <div id="shareAmount">
                <input type="checkbox" name="hide-amount" value="hide-amount" id="hide-amount" /> 
                <label id="labelShareAmount" for="hide-amount">share amount</label>
            </div>
            <div id="share" class="col">
                <span class="share-box">
                    <input id="share-facebook" type="checkbox" name="share-facebook" value="share-facebook" />
                    <label for="share-facebook" class="btn-share-facebook" href="">facebook</label>
                </span>
                <span class="share-box">
                    <input id="share-twitter" type="checkbox" name="share-twitter" value="share-facebook" />
                    <label for="share-twitter" class="btn-share-twitter" href="">twitter</label>
                </span>
            </div>            
            <!-- AMOUNT -->
            <div class="amount">
                <div class="content">
                    <p>Amount</p>
                    <input id="srcAmount" type="text" placeholder="$25" amount="25">
                    <div class="amount-increase">
                        <a href="" class="more">more</a>
                        <a href="" class="less">less</a>
                    </div>
                </div>
            </div>
            <div class="your-cause-mix">
                <table border="0" id="titlesMix">
                    <tr>
                        <th class="title checkoutText column column1"><div>your cause mix</div></th>
                        <th class="column column2">share</th>
                        <th class="column column3">amount</th>
                        <th class="column column4">points</th>
                    </tr>
                </table>
                <div id="scrollMix" class="scroller">
                    <div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
                    <div class="viewport">
                        <div class="overview">
                            <table id="tableMix" border="0"></table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="points-achived">
                <h5 class="checkoutText">points achieved</h5> 
                <p><span id="fpBasic" class="fpoints-number">68</span><span id="fpExtra" class="fpoints-number">+ 10</span><span id="extra">extra</span></p>
            </div> 
        </div>
        <div id="orgs" class="section">
            <h5 class="checkoutText">nonprofits benefitting from your gift <a id="refresh-alert">Refresh</a></h5>
            <div id="scrollOrgs" class="scrollbarSkin">
                <div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
                <div class="viewport">
                    <div class="overview">
                        <ul class="nonprof-list"></ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="payment" class="section">
        <img class="sslGodaddy" src="<?php echo Router::rel("img")?>misc/GoDaddy-SSL-Cert.jpg"/>  
                <?php
                    $balance=DonationController::getBalance($user);
                    if($balance==0){$disable='disabled="disable"';}
                        echo '<div id="balance">';
                            echo '<img src="'.Router::rel("img").'icons/balance.png"/>';
                            echo '<div id="amount"><span>$'.number_format($balance,2).'</span></div>';
                            echo '<input id="use-balance" type="checkbox" name="use-balance" value="use-balance"'.$disable.' /><span>Use it</span>';
                        echo '</div>';
                   
                ?>
            <div id="submit" class="col last">
                <p>By Clicking the submit button You Agree to Our <a target="_blank" href="<?php echo Router::rel('views');  ?>pages/terms.php">Terms and Conditions</a></p>
                <a id="confirmGiving" href="" class="makeGiving">submit</a>
                <input id="checkoutData" type="hidden"/>
            </div>
        </div>
        
    </div>
</div>
<!-- MODAL DE CONGRATULATION -->
<div id="modalCongratulations">
    <div id="congratulations">
        <h2 id="title">Your giving</h2>
        <div id="content">
            <h6 class="field">Date</h6>
            <?php
                echo '<p id="congratDate">'.date_format(new DateTime(),'m/d/Y').'</p>';
            ?>
            <br>
            <div class="total-giving">
                <h6 class="field">Your Giving</h6>
                
                <table>
                    <tr><td class="cell1">Nonprofits</td>           <td class="cell2" id="amount">$ 27.00</td></tr>
                    <tr><td class="cell1">Transaction costs</td>    <td class="cell2" id="transactionCost">$ 2.00</td></tr>
                </table>
                <div class="line"></div>
                <table>
                    <tr><td class="cell1">Total</td>           <td class="cell2" id="total">$ 30.00</td></tr>
                </table>
            </div>
        </div>
        <div id="actions" class="field">
            <p> <a id="email" href="">E-mail</a><!--<a id="pdf" href="">Save pdf</a>   <a id="print" href="">Print receipt</a>--></p>
        </div>
    </div>
</div>
<!-- Modal que solicita los datos de una tarjeta de crédito-->
<div id="modalCreditCard">
<!-- MODAL GENERATOR -->
    <div id="creditCard" class="modal">
        <h1 id="title">Credit card payment</h1>
        <div id="payment-method">    
            <div class="option"><input type="radio" name="methods" value="master">
                <img src="<?php echo Router::rel('img'); ?>icons-payment/Master-40.png">
            </div>
            <div class="option"><input type="radio" name="methods" value="visa">
                <img src="<?php echo Router::rel('img'); ?>icons-payment/Visa-40.png">
            </div>
            <div class="option">
                <span><input type="radio" name="methods" value="discover"></span>
                <img src="<?php echo Router::rel('img'); ?>icons-payment/Discover-40.png">
            </div>
            <div class="option"><input type="radio" name="methods" value="amex" siza="2">
                <img src="<?php echo Router::rel('img'); ?>icons-payment/Amex-40.png">
            </div>
            <div class="option"><input type="radio" name="methods" value="diners">
                <img src="<?php echo Router::rel('img'); ?>icons-payment/diners.png" width="47">
            </div>
        </div>
        <h2 id="subtitle">Card number</h2>

        <div id="data">
            <div id="submit" class="col last">
                <label for="number">Enter you card number</label>
                <input class="number" id="number" maxlength="19" type="text" placeholder="xxxx xxxx xxxx xxxx">
                <label for="month">Expiration Date</label>
                <div id="date">
                    <select name="month" id="month" size="1">
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                    <select name="year" id="year" size="1">
                        <?php
                            $year=date("Y");
                            for($i=0;$i<20;$i++){
                                echo '<option value="'.($year+$i).'">'.($year+$i).'</option>';
                            }
                        ?>
                    </select>
                </div>
                <label for="code">Verification Code</label>
                <input id="code" type="text" placeholder="XXXXXX">

            </div>
                            <h2 id="subtitle"> </h2>
                <input id="name" type="text" placeholder="Name"><br/>
                <input id="lastname" type="text" placeholder="Lastname">
        </div>
        <div id="btn-credit">
            <img id="stripesecure" src="<?php echo Router::rel("img"); ?>misc/glossy.png">
        <p>
        By Clicking the submit button You Agree to Our
        <a href="../../views/pages/terms.php" target="_blank">Terms and Conditions</a>
        </p>
        <input id="accept" class="btn-creditCard" type="submit" value="Send">
        </div>
    </div>
</div>