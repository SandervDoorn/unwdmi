package Parsing;

import org.json.JSONObject;
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xml.sax.InputSource;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import java.io.StringReader;
import java.math.BigDecimal;
import java.math.RoundingMode;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

public class XMLParser {
    private String xml;
    private List<Double> previousTemperatures = new ArrayList<>();
    private List<Double> previousDewpoints = new ArrayList<>();
    private static final Integer[] RELEVANT_STATIONS = new Integer[]{690020,690070,690110,690140,690150,690160,690190,690230,690514,691174,691494,691784,691810,697040,697564,698064,699604,700001,700260,700634,700637,700860,701040,701043,701160,701170,701330,701333,701486,701620,701730,701740,701780,701940,702000,702031,702060,702070,702120,702190,702220,702310,702315,702350,702490,702510,702590,702600,702610,702615,702640,702647,702650,702670,702700,702710,702716,702720,702725,702730,702735,702740,702750,702756,702757,702758,702770,702910,702923,702960,702980,703050,703080,703160,703165,703210,703260,703263,703330,703400,703410,703430,703500,703600,703604,703605,703610,703620,703625,703626,703650,703710,703810,703835,703860,703870,703880,703894,703950,703980,704140,704540,704545,704851,704890,704896,720113,720120,720137,720141,720151,720170,720172,720202,720257,720258,720259,720261,720262,720265,720266,720268,720273,720274,720275,720277,720278,720279,720281,720282,720283,720284,720286,720287,720288,720296,720298,720299,720303,720305,720306,720307,720308,720309,720313,720314,720317,720318,720319,720320,720321,720322,720327,720330,720340,720343,720344,720345,720347,720348,720351,720354,720356,720357,720358,720359,720366,720367,720368,720369,720371,720376,720381,720391,720392,720393,720397,720398,720404,720408,722003,722006,722007,722010,722011,722012,722014,722015,722016,722020,722022,722024,722025,722026,722029,722030,722031,722032,722033,722034,722037,722038,722039,722040,722041,722042,722044,722045,722049,722050,722051,722052,722053,722054,722055,722056,722057,722059,722060,722064,722065,722066,722067,722068,722069,722070,722071,722072,722073,722074,722075,722076,722078,722079,722080,722081,722082,722085,722086,722089,722090,722091,722092,722093,722094,722095,722096,722101,722103,722104,722106,722107,722108,722109,722110,722111,722112,722113,722114,722115,722116,722119,722122,722124,722125,722127,722129,722130,722132,722133,722135,722136,722137,722138,722140,722141,722142,722143,722144,722145,722146,722147,722149,722151,722152,722154,722155,722156,722157,722158,722159,722160,722164,722165,722166,722167,722168,722170,722171,722172,722174,722175,722177,722178,722179,722180,722181,722182,722183,722185,722186,722187,722188,722189,722190,722191,722194,722195,722196,722197,722198,722199,722200,722201,722202,722204,722209,722210,722211,722212,722213,722214,722215,722220,722221,722223,722225,722226,722230,722235,722241,722243,722245,722246,722247,722249,722250,722251,722252,722255,722256,722260,722261,722265,722266,722267,722268,722269,722270,722275,722276,722279,722280,722284,722285,722286,722287,722290,722291,722300,722310,722314,722315,722316,722317,722319,722320,722324,722332,722334,722338,722340,722342,722343,722345,722346,722347,722348,722350,722351,722354,722356,722357,722358,722359,722361,722362,722363,722364,722366,722369,722390,722400,722404,722405,722406,722408,722410,722416,722420,722422,722423,722427,722429,722430,722435,722436,722444,722445,722446,722447,722448,722469,722470,722479,722480,722484,722485,722486,722487,722488,722489,722499,722500,722505,722506,722510,722515,722516,722517,722520,722523,722524,722525,722526,722527,722530,722533,722535,722536,722537,722539,722540,722541,722542,722543,722544,722545,722547,722550,722552,722553,722554,722555,722556,722560,722570,722575,722576,722577,722580,722581,722583,722585,722586,722587,722588,722589,722590,722593,722594,722595,722596,722597,722598,722599,722600,722610,722615,722618,722620,722630,722636,722637,722640,722648,722650,722655,722656,722657,722660,722665,722670,722673,722675,722676,722677,722678,722680,722682,722683,722686,722687,722688,722689,722690,722695,722696,722700,722710,722725,722726,722728,722730,722735,722740,722745,722747,722748,722749,722764,722780,722783,722784,722785,722786,722788,722789,722800,722801,722805,722806,722820,722821,722822,722860,722865,722866,722867,722868,722869,722874,722880,722885,722886,722897,722899,722900,722901,722903,722904,722906,722907,722908,722909,722910,722915,722920,722925,722926,722927,722930,722931,722934,722950,722955,722956,722970,722972,722974,722975,722976,722977,722978,723011,723013,723020,723030,723035,723037,723040,723046,723055,723060,723065,723066,723067,723068,723069,723074,723075,723079,723080,723084,723085,723086,723087,723090,723095,723096,723098,723100,723104,723105,723106,723108,723109,723110,723114,723115,723117,723118,723119,723120,723122,723123,723124,723125,723126,723139,723140,723143,723144,723145,723146,723147,723148,723150,723156,723170,723171,723174,723177,723181,723183,723190,723193,723194,723200,723230,723235,723240,723246,723260,723265,723270,723273,723284,723290,723291,723300,723301,723306,723307,723320,723340,723345,723346,723347,723400,723401,723403,723405,723406,723407,723408,723409,723415,723416,723417,723418,723419,723424,723425,723429,723434,723436,723439,723440,723441,723443,723444,723445,723446,723447,723448,723449,723484,723489,723490,723495,723510,723520,723525,723526,723527,723528,723529,723530,723535,723536,723537,723540,723544,723545,723546,723550,723555,723556,723560,723564,723565,723566,723570,723575,723600,723601,723604,723625,723626,723627,723628,723629,723630,723635,723650,723654,723656,723658,723663,723676,723677,723700,723710,723721,723723,723740,723747,723750,723754,723755,723758,723760,723761,723783,723788,723800,723801,723805,723810,723815,723816,723820,723825,723830,723840,723850,723860,723865,723870,723890,723898,723910,723925,723926,723927,723930,723940,723960,723965,724006,724007,724008,724010,724014,724016,724017,724019,724020,724026,724030,724035,724036,724037,724040,724043,724045,724050,724053,724055,724058,724060,724065,724066,724067,724070,724074,724075,724080,724085,724086,724088,724089,724090,724093,724094,724095,724096,724097,724100,724105,724106,724110,724113,724115,724116,724118,724120,724125,724127,724140,724165,724170,724175,724176,724177,724210,724220,724230,724233,724235,724236,724237,724238,724240,724243,724250,724270,724271,724273,724275,724276,724280,724284,724285,724286,724287,724288,724290,724294,724295,724296,724297,724298,724303,724320,724330,724335,724336,724338,724339,724340,724345,724347,724350,724354,724356,724361,724365,724370,724371,724373,724375,724380,724384,724385,724386,724388,724390,724395,724396,724397,724400,724450,724453,724454,724455,724456,724457,724458,724460,724461,724463,724466,724467,724468,724475,724490,724500,724502,724504,724505,724506,724507,724508,724509,724510,724515,724516,724517,724518,724519,724550,724555,724556,724560,724565,724567,724580,724585,724586,724620,724625,724627,724635,724640,724645,724650,724655,724660,724663,724665,724666,724673,724674,724675,724676,724677,724680,724684,724685,724689,724690,724695,724697,724698,724699,724700,724720,724721,724750,724754,724755,724756,724760,724765,724767,724769,724776,724796,724797,724800,724810,724815,724828,724830,724833,724835,724836,724837,724838,724839,724844,724846,724850,724851,724855,724860,724880,724885,724915,724916,724917,724920,724926,724927,724930,724935,724936,724937,724938,724940,724945,724946,724955,724956,724957,724970,724975,724988,725015,725016,725020,725025,725027,725029,725030,725033,725035,725036,725037,725038,725040,725045,725046,725054,725059,725060,725061,725063,725064,725065,725066,725067,725068,725069,725070,725073,725075,725079,725080,725084,725085,725086,725087,725088,725090,725095,725097,725098,725101,725103,725104,725105,725107,725109,725110,725111,725113,725114,725115,725116,725118,725119,725120,725124,725125,725126,725127,725128,725130,725135,725140,725145,725146,725150,725155,725156,725157,725165,725170,725180,725185,725187,725190,725194,725196,725197,725200,725205,725206,725207,725208,725210,725214,725216,725217,725224,725229,725235,725240,725245,725246,725247,725250,725254,725256,725260,725265,725266,725267,725270,725280,725287,725290,725300,725305,725306,725310,725314,725315,725316,725317,725320,725326,725327,725330,725335,725336,725337,725340,725342,725345,725346,725347,725348,725349,725350,725354,725360,725366,725370,725373,725374,725375,725376,725377,725378,725380,725383,725384,725390,725394,725395,725396,725404,725405,725406,725407,725408,725409,725414,725415,725416,725417,725418,725424,725430,725434,725440,725450,725453,725455,725456,725457,725460,725461,725462,725465,725466,725467,725468,725469,725470,725472,725473,725474,725475,725476,725479,725480,725484,725485,725486,725487,725488,725489,725496,725497,725499,725500,725510,725512,725513,725515,725520,725524,725525,725526,725527,725530,725533,725540,725541,725555,725556,725560,725564,725565,725566,725570,725610,725620,725621,725624,725625,725626,725627,725628,725630,725634,725635,725636,725640,725645,725650,725660,725665,725670,725685,725686,725690,725700,725705,725715,725716,725717,725720,725724,725740,725741,725744,725745,725750,725755,725760,725763,725765,725775,725776,725780,725785,725800,725805,725810,725816,725820,725825,725830,725845,725846,725847,725860,725861,725864,725865,725866,725867,725895,725905,725910,725915,725920,725945,725946,725955,725957,725958,725959,725970,725975,726000,726010,726030,726040,726050,726055,726056,726060,726070,726071,726083,726088,726090,726100,726114,726115,726116,726130,726145,726155,726160,726163,726164,726166,726170,726183,726185,726196,726200,726210,726221,726223,726225,726227,726228,726355,726357,726360,726364,726370,726375,726379,726380,726384,726385,726387,726390,726391,726394,726395,726400,726404,726405,726409,726410,726413,726414,726415,726416,726417,726418,726419,726424,726425,726426,726427,726430,726435,726436,726437,726438,726440,726444,726449,726450,726452,726455,726456,726457,726458,726460,726461,726463,726464,726465,726466,726467,726468,726480,726482,726487,726498,726499,726500,726502,726503,726504,726505,726506,726507,726508,726509,726510,726514,726515,726516,726517,726518,726525,726540,726544,726545,726546,726547,726548,726549,726550,726553,726554,726555,726556,726557,726558,726559,726560,726561,726562,726563,726564,726565,726566,726567,726568,726569,726574,726575,726576,726577,726578,726579,726580,726583,726584,726585,726586,726587,726588,726589,726590,726593,726596,726603,726620,726625,726626,726650,726654,726660,726665,726667,726675,726676,726679,726680,726682,726685,726686,726700,726710,726720,726766,726770,726775,726776,726777,726785,726790,726796,726797,726798,726810,726813,726815,726816,726817,726818,726830,726835,726836,726837,726838,726865,726871,726873,726876,726880,726881,726883,726885,726886,726901,726904,726910,726917,726930,726940,726950,726958,726959,726980,726985,726986,726987,726988,727340,727344,727345,727347,727370,727410,727415,727417,727430,727434,727435,727436,727437,727440,727444,727445,727449,727450,727452,727453,727454,727455,727456,727457,727458,727459,727466,727467,727468,727470,727473,727474,727475,727476,727477,727478,727480,727486,727490,727497,727503,727504,727505,727506,727507,727508,727514,727515,727517,727530,727533,727535,727550,727555,727556,727566,727572,727573,727575,727576,727584,727640,727645,727670,727675,727676,727680,727685,727686,727687,727720,727730,727750,727755,727770,727790,727796,727810,727815,727825,727826,727827,727830,727834,727836,727840,727845,727846,727850,727854,727855,727856,727857,727883,727885,727890,727910,727918,727920,727923,727925,727926,727927,727928,727930,727934,727935,727937,727938,727970,727975,727976,727985,742010,742060,742070,742071,742077,742079,742300,742513,743312,743700,743920,743941,743945,743946,743950,744104,744652,744653,744655,744656,744657,744658,744659,744661,744662,744663,744665,744672,744860,744864,744865,744900,744904,744905,744910,744915,744989,744994,745046,745048,745056,745058,745060,745090,745160,745310,745430,745431,745700,745940,745946,745966,745980,745985,746061,746120,746710,746715,746716,746925,746929,746930,746935,746936,746939,746940,746941,746943,747020,747040,747185,747187,747188,747320,747330,747335,747340,747355,747400,747540,747685,747686,747688,747750,747760,747770,747804,747808,747810,747812,747880,747900,747910,747915,747930,747940,747945,747946,747950,787000,787030,787050,787080,787110,787170,787190,787200,787240,787300,787350,787390,787410,787450,749025,749026,749027,749028,749035,787620,787625,787670,787740,786630,786660,786270,786311,786370,786410,786470,785830,760013,760053,760500,760610,760753,760754,761180,761300,761510,761600,762250,762253,762430,762530,762550,762555,762580,762863,763050,763420,763503,763615,763820,763900,763940,763943,763944,763993,764050,764055,764056,764120,764125,764235,764580,764591,764593,764915,764990,765255,765390,765480,765491,765493,765494,765560,765710,765773,765905,765906,766010,766011,766013,766127,766133,766250,766340,766342,766440,766443,766490,766491,766493,766534,766540,766546,766580,766655,766753,766790,766793,766850,766870,766910,766913,766920,766950,767260,767383,767410,767433,767441,767493,767500,767502,767584,767755,768053,768056,768430,768485,768556,769043};

    /**
     * @param xml
     */
    public XMLParser(String xml) {
        this.xml = xml;
    }

    /**
     * The main parsing function used in the handler thread
     *
     * @return JSONObject
     */
    public JSONObject parseXML() throws Exception
    {
        JSONObject container = new JSONObject();
        ArrayList<JSONObject> json = new ArrayList<>();

        try {
            Document xmlFile = this.getXMLFromString(this.xml);
            NodeList nodeList = xmlFile.getDocumentElement().getChildNodes();

            for (int i = 0; i < nodeList.getLength() - 1; i ++) {
                Node node =  nodeList.item(i);

                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    Element element = (Element) node;
                    Double temperature = null;
                    Double dewpoint = null;

                    String time = element.getElementsByTagName("TIME")
                            .item(0).getChildNodes().item(0).getNodeValue();
                    String lastTwo = time.substring(time.length() - 2);

                    //We only send parse and send data every 10 seconds, check for it here
                    if (Integer.parseInt(lastTwo) % 10 != 0) {
                        return null;
                    }

                    Integer stationCode = Integer.parseInt(this.getValueFromElement("STN", element));

                    if (Arrays.asList(XMLParser.RELEVANT_STATIONS).contains(stationCode)) {
                        String date = this.getValueFromElement("DATE", element);

                        if (element.getElementsByTagName("TEMP").item(0).getChildNodes().item(0) != null) {
                            temperature = Double.parseDouble(this.getValueFromElement("TEMP", element));
                        }

                        temperature = this.roundNumber(this.updatePreviousMeasurements("TEMP", temperature));

                        if (element.getElementsByTagName("DEWP").item(0).getChildNodes().item(0) != null) {
                            dewpoint = Double.parseDouble(this.getValueFromElement("DEWP", element));
                        }

                        dewpoint = this.updatePreviousMeasurements("DEWP", dewpoint);

                        Double humidity = this.calculateHumidity(dewpoint, temperature);
                        humidity = this.roundNumber(humidity);

                        JSONObject jsonElement = new JSONObject();
                        JSONObject measurement = new JSONObject();

                        jsonElement.put("station", stationCode);

                        measurement.put("date", date);
                        measurement.put("time", time);
                        measurement.put("temp", temperature);
                        measurement.put("dewp", dewpoint);
                        measurement.put("hum", humidity);

                        jsonElement.put("measurement", measurement);

                        json.add(jsonElement);
                    }
                }
            }
        } catch (Exception ex) {
            ex.printStackTrace();
        }

        return json.size() > 0 ? container.put("items", json) : null;
    }

    /**
     * Creates a Document object from a String, which is used for XML parsing
     *
     * @param xml
     * @return Document
     * @throws Exception
     */
    private Document getXMLFromString(String xml) throws Exception
    {
        DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
        DocumentBuilder builder = factory.newDocumentBuilder();

        InputSource is = new InputSource(new StringReader(xml));

        return builder.parse(is);
    }

    /**
     * Gets previous measurements, calculates an average, then updates the historic values
     * Also corrects any weird data given by generator
     *
     * @param measurementType
     * @param value
     * @return
     * @throws Exception
     */
    private Double updatePreviousMeasurements(String  measurementType, Double value) throws Exception
    {
        List<Double> list;
        Double average = 0.0;
        Double lastMeasurement = 0.0;

        list = measurementType.equals("TEMP") ? this.previousTemperatures : this.previousDewpoints;

        if (list.size() > 0) {
            average = this.getAverage(list);
            lastMeasurement = list.get(list.size() - 1);
        }

        //If either the value is missing or we are receiving incorrect data, extrapolate from history
        if (value == null) {
            value = average;
        } else if (Math.abs(value) + 5 > 1.2 * (Math.abs(lastMeasurement) + 5) ||
                Math.abs(value) + 5 < 1.2 * (Math.abs(lastMeasurement) + 5) && lastMeasurement != 0) {
            value = lastMeasurement;
        }

        if (measurementType.equals("TEMP")) {
            this.previousTemperatures = this.updateList(list, value);
        } else {
            this.previousDewpoints = this.updateList(list, value);
        }

        return value;
    }

    /**
     * Returns the value of a specified element
     *
     * @param type
     * @param element
     * @return
     */
    private String getValueFromElement(String type, Element element)
    {
        return element.getElementsByTagName(type)
                .item(0).getChildNodes().item(0).getNodeValue();
    }

    /**
     * Calculates the relative humidity from dewpoint and temperature
     *
     * @param dewpoint
     * @param temperature
     * @return
     */
    private Double calculateHumidity(Double dewpoint, Double temperature)
    {
        Double humidity = 100*(
                Math.exp((17.625*dewpoint)/(243.04+dewpoint))/Math.exp((17.625*temperature)/(243.04+temperature))
        );

        return this.roundNumber(humidity);
    }

    /**
     * Adds the value to the list and checks if it needs to remove first entry if list is larger than 30
     *
     * @param list
     * @param value
     * @return
     */
    private List<Double> updateList(List<Double> list, Double value)
    {
        try {
            if (list.size() >= 30) {
                list.remove(0);
            }
        } catch (Exception ex) {
            System.out.println("Could not remove first element from list");
        }

        list.add(value);

        return list;
    }


    /**
     * Rounds a number to two decimals
     *
     * @param value
     * @return
     */
    private Double roundNumber(Double value)
    {
        BigDecimal decimalValue = new BigDecimal(value);
        decimalValue = decimalValue.setScale(2, RoundingMode.HALF_UP);

        return decimalValue.doubleValue();
    }

    /**
     * Returns the average of the given list
     *
     * @param list
     * @return
     */
    private Double getAverage(List<Double> list)
    {
        Double sum = 0.0;

        for (Double historicValue : list) {
            sum += historicValue;
        }

        return sum / list.size();
    }
}
