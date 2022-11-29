<?php

namespace App\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Articles\Article;

class ArticleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $articles = [
	        ['title' => 'Catching up on Adolescent Immunization','overview' => '<p>While visits to the doctor for immunization shots are as regular as clockwork for young children, it is not so for adolescents. When the teen years set in, the zeal and commitment to regular check-ups and doctor visits may not be as easy to maintain or manage. More often than not, school work and extra-curricular activities are given more importance than a visit to the family doctor. Besides, many young children grow up with an aversion to vaccinations, from remembered pains and possibly some other negative experiences related to vaccinations. Then there is the notion that teenagers are immune from sickness.</p>

				<p>Even during adolescence, vaccination needs to be followed as scheduled. For older children who might have missed their schedules, CATCH-UP VACCINATION comes in, as it is intended to protect the recipient from disease as fast a possible, by “completing a person’s recommended vaccination schedule in the shortest but most effective time frame”. To check on whether preteens and teenagers are complete or up-to-date with the proper vaccinations, the doctor is the best person to determine what immunizations have been given and are still warranted at the time of visit.</p>

				<p>Among the recommended vaccines for adolescents are those for diptheria, flu, HPV, meningitis, tetanus and pertussis. The catch-up vaccines that they may need, depending on their immunization record, include chickenpox vaccine, hepatitis B vaccine, measles vaccination, mumps vaccination, polio vaccine and rubella vaccination.</p>

				<p>The Philippine Foundation for Vaccination provides an annual schedule of recommended immunizations for children and adolescents, which lists 13 vaccinations that Filipino children need from age zero to 18 years. The annual schedule is a collaboration of the Philippine Pediatric Society (PPS) and the Pediatric Infectious Disease Society of the Philippines (PIDSP), together with the Philippine Foundation for Vaccination (PFV). Previously, the Department of Health released a recommended schedule of adolescent immunization as part of their Department Memorandum No 2013-0168, defining vaccination priorities for teenagers. The Philippines usually patterns immunization guidelines as released by the Centers for Disease Control (CDC) are usually a collaboration of various societies such as the Advisory Committee on Immunization Practices of the Public Health Service, the American Academy of Pediatrics Committee on Infectious Disease, and the American Academy of Family Practice.</p>

				<p>To facilitate determining the catch up immunization needs, it is best to provide the medical and vaccination history of the adolescent. Baby books and vaccine records will also come in handy.</p>

				<p>Vaccination is not just for young children. The adolescents who are also at risk deserve optimal protection from vaccine preventable diseases.</p>'
	        ,'date' => now(),'image_path' => 'articles/article1.jpg'],

	        ['title' => 'Bromelain Facts: Patient safety, dosage, and interactions','overview' => '<p>Bromelain is known as an enzyme that is extracted from the pineapple’s fruit, juice, or stem. Studies have shown that it may help alleviate symptoms of diarrhea and other digestive problems, osteoarthritis, cardiovascular anomalies, and even cancer.</p>

				<p>While all medicines and supplements go through clinical studies and quality assurance before they become available in the market, there are also certain facts that you need to consider before taking them.</p>

				<p>Patient safety and side effects. Bromelain is deemed safe when taken in appropriate, recommended doses. It can easily be absorbed via the gastrointestinal tract without losing its beneficial properties just like other supplements/medicines. However, there are some common side effects such as stomach discomforts and allergic reactions that is why it is not specifically recommended for those with a strong allergic reaction to pineapple and wheat. As a safety precaution, Bromelain is also not intended for pregnant and breastfeeding mother.</p>

				<p>Dosage. Bromelain may be available in cream for topical use, but the more common preparation known form is via oral pills or tablets. It is safe to take a Bromelain at least 80-400 milligram twice to thrice a day or as prescribed by the doctor.</p>

				<p>Interactions with medications. If you are using other medicines as treatment for any other underlying illnesses, it is best to consult with a doctor or health care provider before using Bromelain. For instance, if you are taking anticoagulant or antiplatelet drugs with Bromelain, this may affect blood clotting and bleeding properties.</p>

				<p>IntegrAid Bromelain by Pharex is an all-natural dietary supplement that helps relieve a person of dyspeptic symptoms. For the recommended dosage and proper intake to ask your doctor about it.</p>'
	        ,'date' => now(),'image_path' => 'articles/article2.jpg'],

	        ['title' => 'Asthma Attack: Signs & Symptoms','overview' => '<p>Asthmatics have many ways to describe the feeling of an asthma attack, ranging from feeling like "fish out of water" to the heaviness of "an elephant sitting on my chest" to coughing that makes it difficult "to catch my breath", or even worse, the sensation of having "a pillow being held over my face".</p>

				<p>The gravity of an asthma attack cannot be overstated. Knowing the signs and symptoms is a good way to prepare and protect oneself from experiencing these feelings that are experienced during an attack.</p>

				<p>The Mayo Clinic list includes severe shortness of breath, chest pains, and coughing or wheezing. Because of the swollen or inflamed airways during an attack, bronchial tubes become narrow from the extra mucus produced.</p>

				<p>If symptoms do not improve after medication or routine treatment, the patient/asthmatic may be experiencing a severe asthma attack. Additional symptoms of such a condition are chest retractions, pale or blue skin, and drowsiness in children. In addition, these conditions may also be present: a fast heartbeat, being too breathless to eat, speak or sleep, confusion or dizziness, and fainting.</p>

				<p>There is an increased risk of a serious attack if any of the following conditions are present:</p>

				<ul>
					<li>Previous severe asthma attack</li>
					<li>Emergency room treatment for asthma</li>
					<li>Previously required intubation for asthma attack</li>
					<li>Existence of other chronic conditions, like sinusitis or lung disease</li>
				</ul>

				<p>The severity of an asthma attack may have dire or life-threatening consequences. In the Philippines, 2017 statistics show that asthma deaths reached 13,186 or 2.13% of total deaths.</p>

				<p>When an attack is imminent or the signs indicate an attack, implement the prescribed routine treatment of your doctor. If there is no improvement, seek emergency care or see your doctor immediately.</p>'
	        ,'date' => now(),'image_path' => 'articles/article3.jpg'],
	    ];

	    foreach ($articles as $article) {
            $this->command->info('Adding article ' . $article['title'] . '...');
            Article::create($article);
    	}
    }
}
