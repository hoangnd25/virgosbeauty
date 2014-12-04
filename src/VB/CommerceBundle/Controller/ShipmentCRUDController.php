<?php
namespace VB\CommerceBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use VB\CommerceBundle\Entity\Product;
use VB\CommerceBundle\Entity\ProductRequest;
use VB\CommerceBundle\Entity\Shipment;
use VB\CommerceBundle\Entity\ShipmentItem;
use VB\CommerceBundle\Form\Type\ShipmentItemsAdminType;

class ShipmentCRUDController extends Controller
{
    /**
     * @Template()
     */
    public function itemsAction()
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());

        /** @var Shipment $object */
        $object = $this->admin->getObject($id);
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new ShipmentItemsAdminType());

        if($this->getRequest()->getMethod() == 'POST'){
            $form->handleRequest($this->getRequest());
            if($form->isValid()){
                $repo = $em->getRepository('VBCommerceBundle:ShipmentItem');
                foreach($form->get('items')->getData() as $id => $data){
                    $item = $repo->find($id);
                    $item->setSaleCommission($data['saleCommission']);
                    if(!$item->getRequest()->getProduct()){
                        $item->setSalePrice($data['salePrice']);
                        $item->setInputPrice($data['inputPrice']);
                    }
                    $em->persist($item);
                }
                $em->flush();
            }
        }

        return $this->render('@VBCommerce/ShipmentCRUD/items.html.twig',array(
            'action'     => 'items',
            'object' => $object,
            'form'  => $form->createView(),
        ));

    }

    /**
     * @Template()
     */
    public function addItemsAction()
    {
        $shipmentId = $this->get('request')->get($this->admin->getIdParameter());

        /** @var Shipment $object */
        $object = $this->admin->getObject($shipmentId);
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new ShipmentItemsAdminType());

        if($this->getRequest()->getMethod() == 'POST'){
            $form->handleRequest($this->getRequest());
            if($form->isValid()){
                $data = $form->getData();
                foreach($data['items'] as $id => $isAdded){
                    if($isAdded){
                        $request =  $em->getRepository('VBCommerceBundle:ProductRequest')->find($id);
                        $item =  new ShipmentItem();
                        $item->setRequest($request);
                        $item->setShipment($object);
                        $request->setStatus(ProductRequest::STATUS_SHIPPED);
                        $em->persist($item);
                    }
                }
                $em->flush();
                return new RedirectResponse($this->admin->generateUrl('items',array('id'=>$shipmentId)));
            }
        }

        $query = $em->getRepository('VBCommerceBundle:ProductRequest')->createQueryBuilder('i')
            ->where('i.status in (:status)')
            ->setParameter('status', array(ProductRequest::STATUS_REQUESTED,''))
            ->getQuery();

        $requests = $query->getResult();

        return $this->render('@VBCommerce/ShipmentCRUD/addItems.html.twig',array(
                'action'     => 'items',
                'object' => $object,
                'form'  => $form->createView(),
                'requests' => $requests
            ));

    }

    public function removeItemAction()
    {
        $itemId = $this->get('request')->get('itemId');
        $shipmentId = $this->get('request')->get($this->admin->getIdParameter());

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $item = $em->getRepository('VBCommerceBundle:ShipmentItem')->find($itemId);
        $item->getRequest()->setStatus(ProductRequest::STATUS_REQUESTED);
        $em->persist($item->getRequest());
        $em->remove($item);

        $em->flush();
        return new RedirectResponse($this->admin->generateUrl('items',array('id'=>$shipmentId)));
    }

    public function excelAction()
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        /** @var Shipment $object */
        $object = $this->admin->getObject($id);

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("virgosbeauty")
            ->setTitle($object->getName())
            ->setSubject($object->getName());
        $phpExcelObject->setActiveSheetIndex(0)->setCellValue('A1', $object->getName());
        $phpExcelObject->getActiveSheet()->mergeCells('A1:G1');
        $phpExcelObject->getActiveSheet()->getRowDimension(1)->setRowHeight(20);
        $phpExcelObject->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true)->setSize(15);
        $phpExcelObject->getActiveSheet()->getStyle('A1:B1')->getAlignment()
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
        ;
        $phpExcelObject->getActiveSheet()->setCellValue('A2', 'Số SP');
        $phpExcelObject->getActiveSheet()->setCellValue('B2', 'Tên SP');
        $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setWidth(50);
        $phpExcelObject->getActiveSheet()->setCellValue('C2', 'Giá AUD');
        $phpExcelObject->getActiveSheet()->setCellValue('D2', 'Giá bán VND');
        $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $phpExcelObject->getActiveSheet()->setCellValue('E2', 'Người bán nhận/SP');
        $phpExcelObject->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $phpExcelObject->getActiveSheet()->setCellValue('F2', 'Hoàn lại/SP');
        $phpExcelObject->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $phpExcelObject->getActiveSheet()->setCellValue('G2', 'Tổng hoàn lại');
        $phpExcelObject->getActiveSheet()->getColumnDimension('G')->setWidth(15);

        /** @var ShipmentItem $item */
        $index = 3;
        foreach($object->getItems() as $item){
            $phpExcelObject->getActiveSheet()->setCellValue('A'.$index, $item->getRequest()->getQuantity());
            $phpExcelObject->getActiveSheet()->setCellValue('B'.$index, $item->getRequest()->getName());
            /** @var Product $product */
            if($product = $item->getRequest()->getProduct()){
                $phpExcelObject->getActiveSheet()->setCellValue('C'.$index, $product->getInputPrice());
                $phpExcelObject->getActiveSheet()->setCellValue('D'.$index, $product->getPrice());
            }else{
                $phpExcelObject->getActiveSheet()->setCellValue('C'.$index, $item->getInputPrice());
                $phpExcelObject->getActiveSheet()->setCellValue('D'.$index, $item->getSalePrice());
            }
            $phpExcelObject->getActiveSheet()->setCellValue('E'.$index, $item->getSaleCommission());
            $phpExcelObject->getActiveSheet()->setCellValue('F'.$index, '=D'.$index.'-E'.$index);
            $phpExcelObject->getActiveSheet()->setCellValue('G'.$index, '=F'.$index.'*A'.$index.'/'.$object->getConversionRate());
            $index++;
        }
        $phpExcelObject->getActiveSheet()->setCellValue('G'.$index, '=SUM(G2:G'.($index-1).')');
        $phpExcelObject->getActiveSheet()->getStyle('G'.$index)->getFont()->setBold(true);

        $phpExcelObject->getActiveSheet()->getStyle('A1:G'.$index)->getFont()->setSize(14);

        $phpExcelObject->getActiveSheet()->setTitle('Sheet1');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename='.$object->getName().'.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
    }
}