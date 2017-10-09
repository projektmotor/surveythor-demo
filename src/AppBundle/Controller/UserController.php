<?php
namespace AppBundle\Controller;

/**
 * UserController
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class UserController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        return array('users' => self::$users);
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function detailAction($id)
    {
        foreach (self::$users as $user) {
            if ($user['id'] == $id) {
                return [ 'user' => $user ];
            }
        }
    }

    /**
     * @var array
     */
    public static $users = [
        ["id"=>3,"name"=>"Theodore","email"=>"sodales.nisi.magna@quislectus.com","address"=>"948-2735 Dolor Av.","city"=>"Barrhead","zip"=>"32586"],
        ["id"=>4,"name"=>"Ingrid","email"=>"aliquet@Vestibulumanteipsum.net","address"=>"P.O. Box 668, 3227 Dictum Avenue","city"=>"Heusweiler","zip"=>"64239-618"],
        ["id"=>5,"name"=>"Jescie","email"=>"metus.In.nec@Sedet.co.uk","address"=>"Ap #145-1159 Arcu Street","city"=>"Wollongong","zip"=>"8306"],
        ["id"=>6,"name"=>"Madeson","email"=>"orci.lacus@enimcondimentum.edu","address"=>"P.O. Box 258, 578 Montes, St.","city"=>"Mulchén","zip"=>"37305"],
        ["id"=>7,"name"=>"Willa","email"=>"Integer.mollis.Integer@erateget.org","address"=>"Ap #564-9297 Tempus Road","city"=>"Idar-Oberstei","zip"=>"3907"],
        ["id"=>8,"name"=>"Jasmine","email"=>"et.ipsum@id.ca","address"=>"1898 Scelerisque, Road","city"=>"Wörgl","zip"=>"64412"],
        ["id"=>9,"name"=>"Preston","email"=>"egestas.Sed.pharetra@loremipsum.ca","address"=>"P.O. Box 704, 3686 Quis Ave","city"=>"Mielec","zip"=>"93413"],
        ["id"=>10,"name"=>"Jerry","email"=>"neque.vitae@molestiedapibusligula.net","address"=>"580-7305 Tempus Rd.","city"=>"Gignod","zip"=>"5079"],
        ["id"=>11,"name"=>"Gary","email"=>"Proin.dolor.Nulla@dictummagna.co.uk","address"=>"P.O. Box 785, 5675 Lorem Rd.","city"=>"Olympia","zip"=>"4478"],
        ["id"=>12,"name"=>"Upton","email"=>"amet.ante@euneque.ca","address"=>"P.O. Box 229, 5475 Molestie Rd.","city"=>"Bienne-lez-Happart","zip"=>"50068"],
        ["id"=>13,"name"=>"Aladdin","email"=>"vulputate@euismod.edu","address"=>"Ap #842-2708 Tempor St.","city"=>"St. Clears","zip"=>"742709"],
        ["id"=>14,"name"=>"William","email"=>"at.libero@placeratCras.net","address"=>"Ap #481-4895 Ipsum Avenue","city"=>"Peterhead","zip"=>"49032"],
        ["id"=>15,"name"=>"Ray","email"=>"non.massa.non@erat.co.uk","address"=>"8473 Mollis. Ave","city"=>"Leonding","zip"=>"54788"],
        ["id"=>16,"name"=>"Quinn","email"=>"magna@anteVivamus.co.uk","address"=>"P.O. Box 504, 1700 Neque. Street","city"=>"Piscinas","zip"=>"9868"],
        ["id"=>17,"name"=>"Rosalyn","email"=>"molestie@egestas.ca","address"=>"9750 Nulla Rd.","city"=>"Falerone","zip"=>"884492"],
        ["id"=>18,"name"=>"Paula","email"=>"Donec@Aliquamnisl.org","address"=>"6134 Urna. Rd.","city"=>"Mission","zip"=>"N8Z 9T3"],
        ["id"=>19,"name"=>"Isaac","email"=>"tellus@sed.net","address"=>"P.O. Box 796, 4171 Duis Rd.","city"=>"Flint","zip"=>"94989"],
        ["id"=>20,"name"=>"Kiayada","email"=>"ac.mattis@scelerisquescelerisque.org","address"=>"976-4907 Nulla Ave","city"=>"Windermere","zip"=>"77176"],
        ["id"=>21,"name"=>"Inga","email"=>"Nulla.tempor.augue@placeratorcilacus.org","address"=>"Ap #816-7787 Lorem St.","city"=>"Holman","zip"=>"81-692"],
        ["id"=>22,"name"=>"Shea","email"=>"enim@Donecfringilla.org","address"=>"280-6240 Nisl Rd.","city"=>"Pescantina","zip"=>"29107"],
        ["id"=>23,"name"=>"Vladimir","email"=>"a.sollicitudin.orci@duinec.edu","address"=>"784-9203 Aliquam Rd.","city"=>"Paisley","zip"=>"98334"],
        ["id"=>24,"name"=>"Veda","email"=>"dui@pharetra.com","address"=>"P.O. Box 902, 8700 Sit Road","city"=>"Grand-Rosi\u017dre-Hottomont","zip"=>"11455-095"],
        ["id"=>25,"name"=>"Indigo","email"=>"non@eleifend.ca","address"=>"P.O. Box 349, 5740 Amet Av.","city"=>"Norcia","zip"=>"62710"],
        ["id"=>26,"name"=>"Cain","email"=>"erat.Etiam.vestibulum@magna.net","address"=>"Ap #825-9161 Pellentesque St.","city"=>"Castel del Giudice","zip"=>"HQ54 1ZP"],
        ["id"=>27,"name"=>"Chaney","email"=>"Aliquam@magna.co.uk","address"=>"P.O. Box 861, 7429 Interdum St.","city"=>"Mont","zip"=>"00-669"],
        ["id"=>28,"name"=>"Jessica","email"=>"Phasellus.elit@liberoMorbiaccumsan.ca","address"=>"888-6632 Dui Road","city"=>"Saint Louis","zip"=>"107521"],
        ["id"=>29,"name"=>"Scarlet","email"=>"amet.risus.Donec@loremvitae.edu","address"=>"4684 Dolor Ave","city"=>"San Antonio","zip"=>"9668 SF"],
        ["id"=>30,"name"=>"Malcolm","email"=>"dolor@lacus.edu","address"=>"Ap #720-7976 Fusce St.","city"=>"Meerhout","zip"=>"J3R 4V4"],
        ["id"=>31,"name"=>"Velma","email"=>"eget.ipsum@insodaleselit.org","address"=>"3356 Suscipit, Ave","city"=>"Zuienkerke","zip"=>"40717"],
        ["id"=>32,"name"=>"Kameko","email"=>"cursus.et@Suspendissecommodo.ca","address"=>"P.O. Box 264, 8473 Urna Street","city"=>"Wilhelmshaven","zip"=>"61204"],
        ["id"=>33,"name"=>"Lynn","email"=>"nec.quam.Curabitur@a.co.uk","address"=>"Ap #640-1991 Proin Street","city"=>"Comano","zip"=>"92433-887"],
        ["id"=>34,"name"=>"Guinevere","email"=>"interdum.Nunc.sollicitudin@odiosempercursus.ca","address"=>"6328 Ipsum Road","city"=>"Sauris","zip"=>"20401"],
        ["id"=>35,"name"=>"Aladdin","email"=>"nulla.In@vestibulumloremsit.co.uk","address"=>"955-9725 Pede Street","city"=>"Aulnay-sous-Bois","zip"=>"29057"],
        ["id"=>36,"name"=>"Prescott","email"=>"lectus@ullamcorpermagnaSed.ca","address"=>"490-7710 Vulputate Av.","city"=>"Paradise","zip"=>"30398"],
        ["id"=>37,"name"=>"Clementine","email"=>"Vivamus.sit@scelerisquelorem.org","address"=>"P.O. Box 311, 9456 Dis Rd.","city"=>"Sundrie","zip"=>"PN2J 6XW"],
        ["id"=>38,"name"=>"Abraham","email"=>"odio@mollis.net","address"=>"1659 Interdum Ave","city"=>"Millport","zip"=>"41701"],
        ["id"=>39,"name"=>"Gareth","email"=>"placerat.velit@risus.ca","address"=>"Ap #695-3670 Vivamus St.","city"=>"Belvedere Ostrense","zip"=>"53-352"]
    ];
}
