</div>
        <footer>
            <p>Copyright <?php echo date("Y", time()); ?> Rafael Adel</p>
        </footer>
    </body>
</html>
<?php 
    if(isset($db)){
        $db->get_connection() != null ? $db->close_connection() : null; 
    }
?>